<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            // Forces every name generated to be completely unique
            'name' => $this->faker->unique()->name(), 
            
            // Forces a completely unique 5-digit number combination
            'student_id_number' => $this->faker->unique()->numberBetween(100000, 999999),
            
            // Forces a completely unique email address
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}