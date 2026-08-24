<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // amount is stored in rupees (human-readable in the admin table) —
    // converted to paise only at the Razorpay Orders API call boundary.
    // status is flipped to paid/failed/refunded by EITHER
    // DonationController::verify() or the Razorpay webhook, whichever
    // gets there first — both are idempotent, so order doesn't matter.
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('donor_name');
            $table->string('donor_email');
            $table->string('donor_phone')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('INR');
            $table->string('razorpay_order_id')->unique();
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_signature')->nullable();
            $table->string('status')->default('pending'); // pending|paid|failed|refunded
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
