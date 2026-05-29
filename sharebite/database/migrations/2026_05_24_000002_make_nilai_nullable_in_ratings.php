<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('ratings') && Schema::hasColumn('ratings', 'nilai')) {
            if (DB::getDriverName() === 'sqlite') {
                Schema::table('ratings', function (Blueprint $table) {
                    $table->unsignedTinyInteger('nilai')->nullable()->change();
                });
            } else {
                DB::statement('ALTER TABLE `ratings` MODIFY COLUMN `nilai` tinyint unsigned NULL');
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ratings') && Schema::hasColumn('ratings', 'nilai')) {
            if (DB::getDriverName() === 'sqlite') {
                Schema::table('ratings', function (Blueprint $table) {
                    $table->unsignedTinyInteger('nilai')->nullable(false)->default(0)->change();
                });
            } else {
                DB::statement('ALTER TABLE `ratings` MODIFY COLUMN `nilai` tinyint unsigned NOT NULL DEFAULT 0');
            }
        }
    }
};
