<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Errors\SignatureVerificationError;
use Razorpay\Api\Utility;

/**
 * Server-to-server from Razorpay, not a user-facing form — no auth/CSRF
 * (routes/api.php is already stateless), own looser throttle (see
 * routes/api.php). This is the durable backup path that keeps
 * Donation.status correct even if the browser closes after payment but
 * before the frontend's own /donations/verify call fires — it must be
 * capable of bringing a Donation to its correct final state entirely on
 * its own, not just mirror what verify() already did.
 *
 * Uses a DIFFERENT signature check than DonationController::verify() —
 * verifyWebhookSignature() (raw-body HMAC against webhook_secret), not
 * verifyPaymentSignature() (the 3-field order/payment/signature check).
 * Treating these as interchangeable is a common real integration bug.
 */
class RazorpayWebhookController extends Controller
{
    // POST /api/v1/webhooks/razorpay
    public function handle(Request $request): Response
    {
        $settings = PaymentSetting::current();

        if (! $settings->webhook_secret) {
            Log::warning('Razorpay webhook received but no webhook_secret is configured.');

            return response('Webhook not configured', 400);
        }

        $payload = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature');

        try {
            (new Utility())->verifyWebhookSignature($payload, $signature, $settings->webhook_secret);
        } catch (SignatureVerificationError $e) {
            Log::warning('Razorpay webhook signature verification failed.');

            return response('Invalid signature', 400);
        }

        $event = json_decode($payload, true);
        $eventType = $event['event'] ?? null;
        $orderId = $event['payload']['payment']['entity']['order_id'] ?? null;
        $paymentId = $event['payload']['payment']['entity']['id'] ?? null;

        if (! $orderId) {
            return response('OK', 200);
        }

        $donation = Donation::where('razorpay_order_id', $orderId)->first();

        if (! $donation) {
            return response('OK', 200);
        }

        $newStatus = match ($eventType) {
            'payment.captured' => 'paid',
            'payment.failed' => 'failed',
            'refund.created' => 'refunded',
            default => null,
        };

        // Idempotent — only write if this event actually changes the
        // current status, so replayed/duplicate webhook deliveries
        // (Razorpay retries on a non-2xx response) don't cause redundant
        // writes or clobber a more advanced status (e.g. a late
        // "captured" event arriving after a "refunded" one).
        if ($newStatus !== null && $donation->status !== $newStatus) {
            $donation->update(array_filter([
                'status' => $newStatus,
                'razorpay_payment_id' => $newStatus === 'paid' ? $paymentId : null,
            ]));
        }

        return response('OK', 200);
    }
}
