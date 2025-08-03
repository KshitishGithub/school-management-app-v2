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
        Schema::create('exams_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('exam_id');
            $table->string('subject');
            $table->string('exam_date');
            $table->string('exam_day');
            $table->string('start_time');
            $table->string('end_time');
            $table->string('full_marks');
            $table->string('pass_marks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams_subjects');
    }
};
