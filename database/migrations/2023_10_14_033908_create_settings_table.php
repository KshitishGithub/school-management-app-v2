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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('school_name',50);
            $table->string('logo',50)->nullable();
            $table->string('favicon',50)->nullable();
            $table->string('village',50);
            $table->string('post_office',50);
            $table->string('police_station',50);
            $table->string('district',50);
            $table->string('pin_code',50);
            $table->string('state',20);
            $table->string('country',20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
