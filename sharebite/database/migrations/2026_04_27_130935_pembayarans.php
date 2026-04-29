<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->unique()->constrained('pesanans')->cascadeOnDelete();
            // 'menunggu' | 'berhasil' | 'gagal'
            $table->enum('status', ['menunggu', 'berhasil', 'gagal'])->default('menunggu');
            // path file QR code (disimpan di storage)
            $table->string('qrcode', 255)->nullable();
            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};