<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Сначала преобразуем old enum в VARCHAR чтобы избежать ошибок
        Schema::table('attendance', function (Blueprint $table) {
            $table->string('status', 50)->change();
        });

        // Преобразуем старые значения в новые
        // absent -> absent_unjustified (по умолчанию считаем без уважительной причины)
        DB::table('attendance')
            ->where('status', 'absent')
            ->update(['status' => 'absent_unjustified']);

        // Теперь обновляем enum для статусов посещаемости
        Schema::table('attendance', function (Blueprint $table) {
            $table->enum('status', [
                'present',
                'late',
                'absent_justified',
                'absent_unjustified'
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Преобразуем обратно в VARCHAR
        Schema::table('attendance', function (Blueprint $table) {
            $table->string('status', 50)->change();
        });

        // Преобразуем обратно
        DB::table('attendance')
            ->where('status', 'absent_unjustified')
            ->update(['status' => 'absent']);

        DB::table('attendance')
            ->where('status', 'absent_justified')
            ->update(['status' => 'absent']);

        // Откатываем enum обратно к старым статусам
        Schema::table('attendance', function (Blueprint $table) {
            $table->enum('status', [
                'present',
                'absent',
                'late'
            ])->change();
        });
    }
};
