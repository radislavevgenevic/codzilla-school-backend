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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "Python для начинающих - Группа А"
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->integer('max_students')->default(22);
            $table->integer('current_students')->default(0);
            $table->enum('status', ['forming', 'active', 'completed', 'cancelled'])->default('forming');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('course_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
