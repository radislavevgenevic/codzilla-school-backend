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
        Schema::table('users', function (Blueprint $table) {
            // Изменяем enum, добавляя 'teacher'
            $table->enum('role', ['admin', 'parent', 'teacher'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Откатываем enum обратно
            $table->enum('role', ['admin', 'parent'])->change();
        });
    }
};
