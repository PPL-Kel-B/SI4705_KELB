<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('unit_bisnis_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('unit_bisnis_profiles', 'nama_bisnis')) {
                $table->string('nama_bisnis', 255)->nullable()->after('nama_usaha');
            }
        });
    }

    public function down(): void
    {
        Schema::table('unit_bisnis_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('unit_bisnis_profiles', 'nama_bisnis')) {
                $table->dropColumn('nama_bisnis');
            }
        });
    }
};
