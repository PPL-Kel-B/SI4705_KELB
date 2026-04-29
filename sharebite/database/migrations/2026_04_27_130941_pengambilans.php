<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengambilans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->cascadeOnDelete();
            // user yang mengambil (role: individu atau komunitas)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal');
            $table->integer('jumlah_porsi')->unsigned();
            $table->string('kode_unik', 100);
            // 'menunggu' | 'dikonfirmasi' | 'selesai'
            $table->enum('status', ['menunggu', 'dikonfirmasi', 'selesai'])->default('menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengambilans');
    }
};