<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Администратор
        User::create([
            'name' => 'Администратор',
            'email' => 'admin@kodzilla.ru',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '+7 (999) 123-45-67',
        ]);

        // Родители
        $parents = [
            [
                'name' => 'Иванов Иван Петрович',
                'email' => 'ivanov@example.com',
                'password' => Hash::make('parent123'),
                'role' => 'parent',
                'phone' => '+7 (999) 111-22-33',
            ],
            [
                'name' => 'Петрова Мария Сергеевна',
                'email' => 'petrova@example.com',
                'password' => Hash::make('parent123'),
                'role' => 'parent',
                'phone' => '+7 (999) 444-55-66',
            ],
            [
                'name' => 'Сидоров Алексей Владимирович',
                'email' => 'sidorov@example.com',
                'password' => Hash::make('parent123'),
                'role' => 'parent',
                'phone' => '+7 (999) 777-88-99',
            ],
        ];

        foreach ($parents as $parent) {
            User::create($parent);
        }
    }
}
