<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Static, admin-managed donation details (bank/UPI/international) —
    // distinct from the Razorpay online-payment integration. `fields` is
    // a flexible label->value JSON map (e.g. {"Account Name": "...",
    // "IFSC": "..."}) since bank/UPI/international each need different
    // fields; the QR image is its own media collection, not nested in
    // this JSON (binary assets can't live in a JSON column).
    public function up(): void
    {
        Schema::create('donation_methods', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // bank|upi|international
            $table->string('title');
            $table->json('fields')->nullable();
            $table->text('instructions')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donation_methods');
    }
};
