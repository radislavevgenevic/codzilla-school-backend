<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            [
                'name' => 'Python - Группа А (утро)',
                'course_id' => 1,
                'max_students' => 10,
                'current_students' => 0,
                'status' => 'active',
                'description' => 'Утренняя группа по Python. Занятия по вторникам и четвергам в 10:00',
            ],
            [
                'name' => 'Python - Группа Б (вечер)',
                'course_id' => 1,
                'max_students' => 12,
                'current_students' => 0,
                'status' => 'forming',
                'description' => 'Вечерняя группа по Python. Занятия по средам и пятницам в 18:00',
            ],
            [
                'name' => 'HTML/CSS - Группа А (утро)',
                'course_id' => 2,
                'max_students' => 8,
                'current_students' => 0,
                'status' => 'active',
                'description' => 'Создание сайтов. Занятия по понедельникам и средам в 11:00',
            ],
            [
                'name' => 'Scratch - Группа А (вечер)',
                'course_id' => 3,
                'max_students' => 6,
                'current_students' => 0,
                'status' => 'active',
                'description' => 'Разработка игр. Занятия по субботам в 15:00',
            ],
        ];

        foreach ($groups as $group) {
            Group::create($group);
        }

        // Добавляем учеников в группы
        // Python группа А: ученики 1, 2, 3
        $group1 = Group::find(1);
        $group1->students()->attach([1, 2, 3], [
            'enrolled_at' => now(),
            'status' => 'active',
        ]);
        $group1->update(['current_students' => 3]);

        // HTML/CSS группа: ученики 4, 5
        $group3 = Group::find(3);
        $group3->students()->attach([4, 5], [
            'enrolled_at' => now(),
            'status' => 'active',
        ]);
        $group3->update(['current_students' => 2]);

        // Scratch группа: ученик 6
        $group4 = Group::find(4);
        $group4->students()->attach([6], [
            'enrolled_at' => now(),
            'status' => 'active',
        ]);
        $group4->update(['current_students' => 1]);
    }
}
