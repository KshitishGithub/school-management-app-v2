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
            $table->string('firebase_token')->nullable()->after('country');
            $table->string('one_signal_api_key')->nullable()->after('firebase_token');
            $table->string('one_signal_app_id')->nullable()->after('one_signal_api_key');
            $table->string('registration_prefix')->nullable()->after('one_signal_app_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('firebase_token');
            $table->dropColumn('one_signal_api_key');
            $table->dropColumn('one_signal_app_id');
            $table->dropColumn('registration_prefix');
        });
    }
};
