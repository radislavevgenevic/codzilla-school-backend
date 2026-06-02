<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_extensions', function (Blueprint $table) {
            $table->foreignId('attendance_id')
                ->nullable()
                ->after('created_by')
                ->constrained('attendance')
                ->nullOnDelete();

            $table->unique('attendance_id');
        });
    }

    public function down(): void
    {
        Schema::table('subscription_extensions', function (Blueprint $table) {
            $table->dropUnique(['attendance_id']);
            $table->dropConstrainedForeignId('attendance_id');
        });
    }
};
