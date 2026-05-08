<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'age_from',
        'age_to',
        'description',
        'price',
        'duration_weeks',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'age_from' => 'integer',
        'age_to' => 'integer',
    ];

    // Связи
    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_student')
            ->withPivot('enrolled_at', 'graduated_at', 'status')
            ->withTimestamps();
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function activeStudents()
    {
        return $this->students()->wherePivot('status', 'enrolled');
    }

    // Аттрибуты
    public function getAgeRangeAttribute(): string
    {
        return "{$this->age_from}-{$this->age_to} лет";
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' ₽';
    }
}
