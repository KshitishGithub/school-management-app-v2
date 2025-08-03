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
        Schema::table('exams_subjects', function (Blueprint $table) {
            $table->string('subjectType')->after('pass_marks')->default('1')->comment('1 is main , 0 is optional');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams_subjects', function (Blueprint $table) {
            $table->dropColumn('subjectType');
        });
    }
};
