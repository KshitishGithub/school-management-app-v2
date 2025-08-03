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
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('transport')->nullable()->default('No')->after('photo');
            $table->string('hostel')->nullable()->default('No')->after('transport');
            $table->string('mess')->nullable()->default('No')->after('hostel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['transport', 'hostel', 'mess']);
        });
    }
};
