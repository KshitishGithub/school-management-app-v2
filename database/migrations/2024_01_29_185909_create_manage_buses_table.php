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
        Schema::create('manage_buses', function (Blueprint $table) {
            $table->id();
            $table->string('bus_name');
            $table->string('bus_type');
            $table->string('bus_number');
            $table->string('total_seat');
            $table->string('driver_name');
            $table->string('driver_mobile');
            $table->string('route');
            $table->string('bus_photo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manage_buses');
    }
};
