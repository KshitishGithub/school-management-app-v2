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
        Schema::create('add_marks', function (Blueprint $table) {
            $table->id();
            $table->string('session');
            $table->string('registration_id');
            $table->string('name');
            $table->string('class');
            $table->string('section');
            $table->string('roll_no');
            $table->string('exam_id');
            $table->string('subject_id');
            $table->string('full_marks');
            $table->string('marks_obtained');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_marks');
    }
};
