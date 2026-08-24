<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Singleton — entered via the admin panel (ManagePaymentSettings), not
    // .env, so a non-technical admin can self-serve their own Razorpay
    // credentials. key_secret/webhook_secret use Laravel's `encrypted`
    // cast on the model — deliberately `text`, not `string(255)`, since
    // encrypted output (AES-256-CBC + base64) is well over 255 chars even
    // for a short secret. Never exposed via any public API Resource.
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key_id')->nullable();
            $table->text('key_secret')->nullable();
            $table->text('webhook_secret')->nullable();
            $table->boolean('is_test_mode')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
