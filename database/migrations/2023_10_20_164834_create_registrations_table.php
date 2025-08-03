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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('session',20);
            $table->string('class',20);
            $table->string('section',10)->nullable();
            $table->string('name',70);
            $table->string('dateOfBirth',20);
            $table->string('fathersName',70);
            $table->string('fathersQualification',30)->nullable();
            $table->string('fathersOccupation',30)->nullable();
            $table->string('mothersName',70);
            $table->string('mothersQualification',30)->nullable();
            $table->string('mothersOccupation',30)->nullable();
            $table->string('mobile',10);
            $table->string('whatsapp',10)->nullable();
            $table->string('nationality',30);
            $table->string('religion',30);
            $table->string('caste',30);
            $table->string('gander',30);
            $table->string('village',40);
            $table->string('postOffice',40);
            $table->string('policeStation',30);
            $table->string('district',40);
            $table->string('pin',6);
            $table->string('aadhar',15)->nullable();
            $table->string('photo',30);
            $table->string('status',30)->nullable();
            $table->string('role',30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
