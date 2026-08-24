<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'donor_name', 'donor_email', 'donor_phone', 'amount', 'currency',
        'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature', 'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];
}
