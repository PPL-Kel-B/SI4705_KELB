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
        // 1. Ubah nama kolomnya dulu
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->renameColumn('tanggal_bayar', 'waktu_bayar');
        });

        // 2. Pastikan tipe datanya adalah DATETIME dan bisa kosong (nullable)
        // (Karena saat QRIS baru dibuat tapi belum dibayar, waktunya harus kosong)
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dateTime('waktu_bayar')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Untuk rollback (mengembalikan ke kondisi semula)
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->renameColumn('waktu_bayar', 'tanggal_bayar');
        });
    }
};
