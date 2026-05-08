<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->integer('completed_lessons_count')->default(0);
            $table->foreignId('current_lesson_id')->nullable()->constrained('lessons')->nullOnDelete();
            $table->decimal('percent', 5, 2)->default(0);
            $table->dateTime('last_attendance_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'course_id']);
            $table->index('student_id');
            $table->index('course_id');
            $table->index('percent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};
