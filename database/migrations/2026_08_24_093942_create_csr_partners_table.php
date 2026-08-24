<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('csr_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo_alt')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            // Logo attaches via Spatie MediaLibrary (see CsrPartner model),
            // not a plain string column — same pattern as TeamMember/photo.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('csr_partners');
    }
};
