<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('registration_number');
            $table->string('phone_number');
            $table->string('email');
            $table->integer('technicians_count');
            $table->integer('years_in_operation');
            $table->text('workshop_address');
            $table->string('state_city');
            $table->text('services_offered');
            $table->string('mobile_mechanic_service');
            $table->string('ip_address', 45)->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
