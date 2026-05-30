<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a Teacher account para may pang-login ka sa website
        User::create([
            'name' => 'Professor Smith',
            'email' => 'teacher@school.edu',
            'password' => Hash::make('password123'),
        ]);

        // 2. Create Students - Saktong 50 records lang na magsisimula sa ID 1 hanggang 50
        // Tinanggal na natin sina Alice, Bob, at Charlie pati ang mga subjects/attendance
        Student::factory()->count(50)->create();
    }
}