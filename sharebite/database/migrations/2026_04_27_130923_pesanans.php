<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            // FK ke menu aktif yang dipesan (bukan master data)
            $table->foreignId('menu_aktif_id')->constrained('menu_aktifs')->cascadeOnDelete();
            $table->foreignId('unit_bisnis_id')->constrained('unit_bisnis_profiles')->cascadeOnDelete();
            // user yang memesan (role: individu atau komunitas)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('jumlah_porsi')->unsigned();
            $table->double('total_harga')->unsigned()->default(0);
            $table->text('catatan')->nullable();
            // 'menunggu_pembayaran' | 'dibayar' | 'siap_diambil' | 'selesai' | 'dibatalkan'
            $table->enum('status', [
                'menunggu_pembayaran',
                'dibayar',
                'siap_diambil',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_pembayaran');
            // kode unik untuk verifikasi pengambilan
            $table->string('kode_unik', 100)->unique();
            $table->timestamp('waktu_pesan')->useCurrent();
            $table->date('waktu_diambil')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};