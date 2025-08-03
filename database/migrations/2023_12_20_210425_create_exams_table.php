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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->string('exam_name');
            $table->string('class');
            $table->string('section')->nullable();
            $table->string('fees');
            $table->string('status')->default('1');  // 1 means exam is running, 0 means exam is not running
            $table->string('is_published')->default('0');  // 0 means not published
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
