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
        Schema::create('total_fees', function (Blueprint $table) {
            $table->id();
            $table->string('s_id');
            $table->string('fees_type');
            $table->string('exam_id')->nullable();
            $table->string('session_id');
            $table->string('month')->nullable();
            $table->string('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('total_fees');
    }
};
