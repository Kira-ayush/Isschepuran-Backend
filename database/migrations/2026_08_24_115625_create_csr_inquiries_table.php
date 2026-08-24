<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('csr_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('organization_name');
            $table->string('contact_person');
            $table->string('email');
            $table->string('country_code');
            $table->string('phone');
            $table->string('budget_range'); // 5l_10l|10l_50l|50l_plus
            $table->text('goals');
            $table->string('status')->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('csr_inquiries');
    }
};
