<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SchedulesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Python группа А (group_id = 1)
        $startDate = Carbon::now()->startOfWeek()->addDays(1); // вторник
        $lessons = [1, 2, 3, 4, 5, 6, 7, 8, 9];

        foreach ($lessons as $index => $lessonId) {
            $date = $startDate->copy()->addWeeks(floor($index / 2));
            $day = $index % 2 === 0 ? 'Tuesday' : 'Thursday';
            $date->setISODate($date->year, $date->weekOfYear, $day === 'Tuesday' ? 2 : 4);

            Schedule::create([
                'lesson_id' => $lessonId,
                'group_id' => 1,
                'start_time' => $date->copy()->setTime(10, 0, 0),
                'end_time' => $date->copy()->setTime(11, 30, 0),
                'room' => 'Кабинет 101',
            ]);
        }

        // HTML/CSS группа (group_id = 3)
        $startDate2 = Carbon::now()->startOfWeek()->addDays(0); // понедельник
        $htmlLessons = [10, 11, 12, 13, 14, 15, 16, 17];

        foreach ($htmlLessons as $index => $lessonId) {
            $date = $startDate2->copy()->addWeeks(floor($index / 2));
            $day = $index % 2 === 0 ? 'Monday' : 'Wednesday';
            $date->setISODate($date->year, $date->weekOfYear, $day === 'Monday' ? 1 : 3);

            Schedule::create([
                'lesson_id' => $lessonId,
                'group_id' => 3,
                'start_time' => $date->copy()->setTime(11, 0, 0),
                'end_time' => $date->copy()->setTime(12, 30, 0),
                'room' => 'Кабинет 202',
            ]);
        }

        // Scratch группа (group_id = 4)
        $startDate3 = Carbon::now()->next(Carbon::SATURDAY);
        $scratchLessons = [18, 19, 20, 21, 22, 23, 24, 25];

        foreach ($scratchLessons as $index => $lessonId) {
            $date = $startDate3->copy()->addWeeks($index);
            Schedule::create([
                'lesson_id' => $lessonId,
                'group_id' => 4,
                'start_time' => $date->setTime(15, 0, 0),
                'end_time' => $date->setTime(16, 30, 0),
                'room' => 'Кабинет 303',
            ]);
        }
    }
}
