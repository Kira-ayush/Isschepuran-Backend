<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Singleton — Razorpay credentials entered via the admin panel
 * (ManagePaymentSettings), not .env, so a non-technical admin can
 * self-serve their own credentials without a developer redeploying.
 * key_secret/webhook_secret are encrypted at rest and must NEVER be
 * exposed via any public API Resource — only PaymentSetting::current()
 * is read, server-side, by DonationController/RazorpayWebhookController.
 */
class PaymentSetting extends Model
{
    protected $fillable = ['key_id', 'key_secret', 'webhook_secret', 'is_test_mode'];

    protected $casts = [
        'key_secret' => 'encrypted',
        'webhook_secret' => 'encrypted',
        'is_test_mode' => 'boolean',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'is_test_mode' => true,
        ]);
    }
}
