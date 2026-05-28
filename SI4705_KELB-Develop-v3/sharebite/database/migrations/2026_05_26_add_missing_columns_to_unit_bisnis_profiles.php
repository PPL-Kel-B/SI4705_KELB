<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('unit_bisnis_profiles', function (Blueprint $table) {
            // Tambah kolom jika belum ada
            if (!Schema::hasColumn('unit_bisnis_profiles', 'email_bisnis')) {
                $table->string('email_bisnis', 255)->nullable();
            }
            
            if (!Schema::hasColumn('unit_bisnis_profiles', 'no_telepon')) {
                $table->string('no_telepon', 20)->nullable();
            }
            
            if (!Schema::hasColumn('unit_bisnis_profiles', 'tipe_bisnis')) {
                $table->string('tipe_bisnis', 100)->nullable();
            }
            
            if (!Schema::hasColumn('unit_bisnis_profiles', 'lokasi_lat')) {
                $table->decimal('lokasi_lat', 10, 7)->nullable();
            }
            
            if (!Schema::hasColumn('unit_bisnis_profiles', 'lokasi_lng')) {
                $table->decimal('lokasi_lng', 10, 7)->nullable();
            }
            
            if (!Schema::hasColumn('unit_bisnis_profiles', 'radius_penjemputan')) {
                $table->integer('radius_penjemputan')->default(15);
            }
            
            if (!Schema::hasColumn('unit_bisnis_profiles', 'jam_buka')) {
                $table->time('jam_buka')->default('08:00');
            }
            
            if (!Schema::hasColumn('unit_bisnis_profiles', 'jam_tutup')) {
                $table->time('jam_tutup')->default('21:00');
            }
            
            if (!Schema::hasColumn('unit_bisnis_profiles', 'foto_bisnis')) {
                $table->string('foto_bisnis', 255)->nullable();
            }
            
            if (!Schema::hasColumn('unit_bisnis_profiles', 'verified')) {
                $table->boolean('verified')->default(false);
            }
            
            if (!Schema::hasColumn('unit_bisnis_profiles', 'tahun_bergabung')) {
                $table->year('tahun_bergabung')->nullable();
            }
            
            if (!Schema::hasColumn('unit_bisnis_profiles', 'notifikasi_aktif')) {
                $table->boolean('notifikasi_aktif')->default(true);
            }
            
            if (!Schema::hasColumn('unit_bisnis_profiles', 'notifikasi_pesanan')) {
                $table->boolean('notifikasi_pesanan')->default(true);
            }
            
            if (!Schema::hasColumn('unit_bisnis_profiles', 'notifikasi_penjemputan')) {
                $table->boolean('notifikasi_penjemputan')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('unit_bisnis_profiles', function (Blueprint $table) {
            $columns = [
                'email_bisnis',
                'no_telepon',
                'tipe_bisnis',
                'lokasi_lat',
                'lokasi_lng',
                'radius_penjemputan',
                'jam_buka',
                'jam_tutup',
                'foto_bisnis',
                'verified',
                'tahun_bergabung',
                'notifikasi_aktif',
                'notifikasi_pesanan',
                'notifikasi_penjemputan',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('unit_bisnis_profiles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
