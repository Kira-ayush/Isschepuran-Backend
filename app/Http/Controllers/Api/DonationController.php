<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDonationOrderRequest;
use App\Http\Requests\VerifyDonationRequest;
use App\Models\Donation;
use App\Models\PaymentSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Razorpay\Api\Utility;

class DonationController extends Controller
{
    // POST /api/v1/donations/create-order
    public function createOrder(CreateDonationOrderRequest $request): JsonResponse
    {
        $settings = PaymentSetting::current();

        if (! $settings->key_id || ! $settings->key_secret) {
            return response()->json(['message' => 'Payment gateway is not configured yet.'], 500);
        }

        $data = $request->validated();

        // Razorpay's Orders API expects the amount in the smallest
        // currency unit (paise for INR) — donations.amount is stored in
        // rupees (human-readable in the admin table); this conversion is
        // the single most common real Razorpay integration bug.
        $amountPaise = (int) round($data['amount'] * 100);

        $api = new Api($settings->key_id, $settings->key_secret);

        try {
            $order = $api->order->create([
                'receipt' => 'donation_' . Str::uuid(),
                'amount' => $amountPaise,
                'currency' => 'INR',
                'payment_capture' => 1,
            ]);
        } catch (\Throwable $e) {
            Log::error('Razorpay order create failed', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Could not start payment. Please try again.'], 502);
        }

        $donation = Donation::create([
            'donor_name' => $data['donorName'],
            'donor_email' => $data['donorEmail'],
            'donor_phone' => $data['donorPhone'] ?? null,
            'amount' => $data['amount'],
            'currency' => 'INR',
            'razorpay_order_id' => $order['id'],
            'status' => 'pending',
        ]);

        return response()->json([
            'orderId' => $order['id'],
            'amount' => $amountPaise,
            'currency' => 'INR',
            'keyId' => $settings->key_id,
            'donationId' => (string) $donation->id,
        ]);
    }

    // POST /api/v1/donations/verify
    public function verify(VerifyDonationRequest $request): JsonResponse
    {
        $data = $request->validated();

        $donation = Donation::where('id', $data['donationId'])
            ->where('razorpay_order_id', $data['razorpayOrderId'])
            ->first();

        if (! $donation) {
            return response()->json(['message' => 'Donation not found.'], 404);
        }

        // Already confirmed (e.g. the webhook got there first) — idempotent,
        // no need to re-verify or overwrite.
        if ($donation->status === 'paid') {
            return response()->json(['status' => 'paid']);
        }

        $settings = PaymentSetting::current();
        new Api($settings->key_id, $settings->key_secret); // populates Api's static key/secret for Utility::getSecret()

        try {
            (new Utility())->verifyPaymentSignature([
                'razorpay_order_id' => $data['razorpayOrderId'],
                'razorpay_payment_id' => $data['razorpayPaymentId'],
                'razorpay_signature' => $data['razorpaySignature'],
            ]);
        } catch (SignatureVerificationError $e) {
            $donation->update(['status' => 'failed']);

            return response()->json(['message' => 'Payment verification failed.'], 422);
        }

        $donation->update([
            'status' => 'paid',
            'razorpay_payment_id' => $data['razorpayPaymentId'],
            'razorpay_signature' => $data['razorpaySignature'],
        ]);

        return response()->json(['status' => 'paid']);
    }
}
