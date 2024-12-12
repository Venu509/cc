<?php

namespace Domain\Student\Seeders;

use Domain\Student\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            [
                'title' => 'Student no 1',

            ],
        ];

        collect($students)->each(function ($student) {
            Student::factory()->create([
                'title' => $student['name'],
            ]);
        });
    }
}
