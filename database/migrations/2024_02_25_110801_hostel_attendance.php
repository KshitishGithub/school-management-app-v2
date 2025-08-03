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
        Schema::create('hostel_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('registration_id');
            $table->string('session');
            $table->string('class');
            $table->string('section')->nullable();
            $table->string('roll');
            $table->string('attendance');
            $table->string('attendance_by');
            $table->string('attendance_from');
            $table->string('attendance_type');
            $table->string('inOutStatus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hostel_attendances');
    }
};
