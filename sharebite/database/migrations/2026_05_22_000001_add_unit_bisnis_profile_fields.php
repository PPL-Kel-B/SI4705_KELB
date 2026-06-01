<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('unit_bisnis_profiles', function (Blueprint $table) {
            // Add new columns for Unit Bisnis profile
            if (!Schema::hasColumn('unit_bisnis_profiles', 'nama_bisnis')) {
                $table->string('nama_bisnis', 100)->nullable()->after('nama_usaha');
            }
            if (!Schema::hasColumn('unit_bisnis_profiles', 'tipe_bisnis')) {
                $table->string('tipe_bisnis', 100)->nullable()->after('jenis_usaha');
            }
            if (!Schema::hasColumn('unit_bisnis_profiles', 'email_bisnis')) {
                $table->string('email_bisnis', 255)->nullable()->after('jenis_usaha');
            }
            if (!Schema::hasColumn('unit_bisnis_profiles', 'no_telepon')) {
                $table->string('no_telepon', 20)->nullable()->after('email_bisnis');
            }
            if (!Schema::hasColumn('unit_bisnis_profiles', 'foto_bisnis')) {
                $table->string('foto_bisnis', 255)->nullable()->after('nib_file');
            }
            if (!Schema::hasColumn('unit_bisnis_profiles', 'lokasi_lat')) {
                $table->decimal('lokasi_lat', 10, 8)->nullable()->after('foto_bisnis');
            }
            if (!Schema::hasColumn('unit_bisnis_profiles', 'lokasi_lng')) {
                $table->decimal('lokasi_lng', 11, 8)->nullable()->after('lokasi_lat');
            }
            if (!Schema::hasColumn('unit_bisnis_profiles', 'radius_penjemputan')) {
                $table->integer('radius_penjemputan')->default(15)->after('lokasi_lng'); // in km
            }
            if (!Schema::hasColumn('unit_bisnis_profiles', 'jam_buka')) {
                $table->time('jam_buka')->default('08:00')->after('radius_penjemputan');
            }
            if (!Schema::hasColumn('unit_bisnis_profiles', 'jam_tutup')) {
                $table->time('jam_tutup')->default('21:00')->after('jam_buka');
            }
            if (!Schema::hasColumn('unit_bisnis_profiles', 'verified')) {
                $table->boolean('verified')->default(false)->after('status_verifikasi');
            }
            if (!Schema::hasColumn('unit_bisnis_profiles', 'tahun_bergabung')) {
                $table->year('tahun_bergabung')->nullable()->after('verified');
            }
            if (!Schema::hasColumn('unit_bisnis_profiles', 'notifikasi_aktif')) {
                $table->boolean('notifikasi_aktif')->default(true)->after('tahun_bergabung');
            }
            if (!Schema::hasColumn('unit_bisnis_profiles', 'notifikasi_pesanan')) {
                $table->boolean('notifikasi_pesanan')->default(true)->after('notifikasi_aktif');
            }
            if (!Schema::hasColumn('unit_bisnis_profiles', 'notifikasi_penjemputan')) {
                $table->boolean('notifikasi_penjemputan')->default(true)->after('notifikasi_pesanan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('unit_bisnis_profiles', function (Blueprint $table) {
            $columns = [
                'nama_bisnis', 'tipe_bisnis', 'email_bisnis', 'no_telepon', 'foto_bisnis',
                'lokasi_lat', 'lokasi_lng', 'radius_penjemputan', 'jam_buka', 'jam_tutup',
                'verified', 'tahun_bergabung', 'notifikasi_aktif', 'notifikasi_pesanan', 'notifikasi_penjemputan'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('unit_bisnis_profiles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
