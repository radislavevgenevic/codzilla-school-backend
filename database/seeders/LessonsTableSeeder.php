<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Seeder;

class LessonsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Уроки для Python курса (course_id = 1)
        $pythonLessons = [
            'Введение в Python. Установка и настройка',
            'Переменные и типы данных',
            'Условные операторы (if-else)',
            'Циклы (for, while)',
            'Списки и кортежи',
            'Функции',
            'Словари и множества',
            'Работа с файлами',
            'Итоговый проект',
        ];

        foreach ($pythonLessons as $index => $title) {
            Lesson::create([
                'course_id' => 1,
                'title' => $title,
                'order' => $index + 1,
                'description' => "Описание урока: $title",
                'materials' => json_encode(['presentation.pptx', 'homework.docx']),
            ]);
        }

        // Уроки для HTML/CSS курса (course_id = 2)
        $htmlLessons = [
            'Что такое HTML? Структура документа',
            'Основные теги HTML',
            'CSS: селекторы и свойства',
            'Блочная модель',
            'Flexbox',
            'Grid Layout',
            'Адаптивный дизайн',
            'Формы и валидация',
        ];

        foreach ($htmlLessons as $index => $title) {
            Lesson::create([
                'course_id' => 2,
                'title' => $title,
                'order' => $index + 1,
                'description' => "Описание урока: $title",
                'materials' => json_encode(['lesson.pdf', 'examples.zip']),
            ]);
        }

        // Уроки для Scratch курса (course_id = 3)
        $scratchLessons = [
            'Знакомство со Scratch',
            'Движение и анимация',
            'Управление спрайтами',
            'Создание простой игры',
            'Работа со звуком',
            'Переменные и операторы',
            'Создание аркады',
            'Презентация проектов',
        ];

        foreach ($scratchLessons as $index => $title) {
            Lesson::create([
                'course_id' => 3,
                'title' => $title,
                'order' => $index + 1,
                'description' => "Описание урока: $title",
                'materials' => json_encode(['scratch_project.sb3']),
            ]);
        }
    }
}
