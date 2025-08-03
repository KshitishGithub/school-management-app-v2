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
        Schema::create('student_leaves', function (Blueprint $table) {
            $table->id();
            $table->string('session');
            $table->string('class');
            $table->string('section')->nullable();
            $table->string('roll');
            $table->string('name');
            $table->text('reasons');
            $table->string('from_date');
            $table->string('to_date');
            $table->string('isApproved')->default('P')->comment('P = pending , A = approved , R = rejected');
            $table->string('approvedBy')->nullable();
            $table->string('letterName');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_leaves');
    }
};
