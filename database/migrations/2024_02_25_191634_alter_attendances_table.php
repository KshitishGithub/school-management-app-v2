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
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('attendance_by')->after('attendance');
            $table->string('attendance_from')->after('attendance_by');
            $table->string('attendance_type')->after('attendance_from');
            $table->string('inOutStatus')->after('attendance_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('attendance_by');
            $table->dropColumn('attendance_from');
            $table->dropColumn('attendance_type');
            $table->dropColumn('inOutStatus');
        });
    }
};
