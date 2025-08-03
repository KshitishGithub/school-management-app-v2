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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('medium')->after('favicon')->nullable();
            $table->string('registration')->after('medium')->nullable();
            $table->string('contact')->after('registration')->nullable();
            $table->string('email')->after('contact')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('medium');
            $table->dropColumn('registration');
            $table->dropColumn('contact');
            $table->dropColumn('email');
        });
    }
};
