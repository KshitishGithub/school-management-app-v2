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
        Schema::table('add_marks', function (Blueprint $table) {
            $table->string('oral_marks')->after('full_marks')->default('0');
            $table->string('oral_marks_obtained')->after('oral_marks')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('add_marks', function (Blueprint $table) {
            //
        });
    }
};
