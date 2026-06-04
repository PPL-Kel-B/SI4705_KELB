<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('unit_bisnis_profiles', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('unit_bisnis_profiles', 'nama_bisnis')) {
                $columns[] = 'nama_bisnis';
            }
            if (Schema::hasColumn('unit_bisnis_profiles', 'email_bisnis')) {
                $columns[] = 'email_bisnis';
            }
            if (Schema::hasColumn('unit_bisnis_profiles', 'no_telepon')) {
                $columns[] = 'no_telepon';
            }
            if (Schema::hasColumn('unit_bisnis_profiles', 'foto_profile')) {
                $columns[] = 'foto_profile';
            }
            if (Schema::hasColumn('unit_bisnis_profiles', 'tipe_bisnis')) {
                $columns[] = 'tipe_bisnis';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }

    public function down(): void
    {
        Schema::table('unit_bisnis_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('unit_bisnis_profiles', 'nama_bisnis')) {
                $table->string('nama_bisnis', 255)->nullable()->after('nama_usaha');
            }
            if (!Schema::hasColumn('unit_bisnis_profiles', 'email_bisnis')) {
                $table->string('email_bisnis', 255)->nullable()->after('jenis_usaha');
            }
            if (!Schema::hasColumn('unit_bisnis_profiles', 'no_telepon')) {
                $table->string('no_telepon', 20)->nullable()->after('email_bisnis');
            }
            if (!Schema::hasColumn('unit_bisnis_profiles', 'foto_profile')) {
                $table->string('foto_profile')->nullable();
            }
            if (!Schema::hasColumn('unit_bisnis_profiles', 'tipe_bisnis')) {
                $table->string('tipe_bisnis', 100)->nullable()->after('jenis_usaha');
            }
        });
    }
};
