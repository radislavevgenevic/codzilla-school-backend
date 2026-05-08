<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            // Дети Иванова
            [
                'full_name' => 'Иванов Дмитрий Иванович',
                'age' => 12,
                'birth_date' => '2012-03-15',
                'gender' => 'male',
                'status' => 'active',
                'parent_id' => 2, // Иванов
            ],
            [
                'full_name' => 'Иванова Анна Ивановна',
                'age' => 10,
                'birth_date' => '2014-07-22',
                'gender' => 'female',
                'status' => 'active',
                'parent_id' => 2, // Иванов
            ],
            // Дети Петровой
            [
                'full_name' => 'Петров Сергей Михайлович',
                'age' => 13,
                'birth_date' => '2011-01-10',
                'gender' => 'male',
                'status' => 'active',
                'parent_id' => 3, // Петрова
            ],
            [
                'full_name' => 'Петрова Екатерина Михайловна',
                'age' => 8,
                'birth_date' => '2016-09-05',
                'gender' => 'female',
                'status' => 'active',
                'parent_id' => 3, // Петрова
            ],
            // Дети Сидорова
            [
                'full_name' => 'Сидоров Артём Алексеевич',
                'age' => 14,
                'birth_date' => '2010-11-30',
                'gender' => 'male',
                'status' => 'active',
                'parent_id' => 4, // Сидоров
            ],
            [
                'full_name' => 'Сидорова Мария Алексеевна',
                'age' => 11,
                'birth_date' => '2013-05-18',
                'gender' => 'female',
                'status' => 'active',
                'parent_id' => 4, // Сидоров
            ],
        ];

        foreach ($students as $student) {
            Student::create($student);
        }
    }
}
