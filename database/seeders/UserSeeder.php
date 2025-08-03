<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add the admin
        DB::table('users')->insert([
            'name' => 'Kshitish Barman',
            'email' => 'official.kshitish@gmail.com',
            'username' => 'Kshitish Barman',
            'phone' => '8759952502',
            'status' => 'Active',
            'role' => '4',
            'profile_image' => '123456.jpg',
            'password' => Hash::make('123456'),
        ]);
    }
}