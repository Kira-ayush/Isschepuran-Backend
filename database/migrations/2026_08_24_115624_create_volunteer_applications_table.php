<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volunteer_applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('country_code');
            $table->string('phone');
            $table->string('area_of_interest'); // reforestation|waste_management|community_education|administrative_support
            $table->text('message');
            $table->string('status')->default('new'); // new|contacted|accepted|declined
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_applications');
    }
};
