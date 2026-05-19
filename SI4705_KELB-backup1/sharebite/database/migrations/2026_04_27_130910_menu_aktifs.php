<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Menu aktif = makanan yang sedang dibuka/dijual oleh unit bisnis.
        // Unit bisnis memilih dari master_makanans, lalu bisa override harga
        // (misal digratisin), set stok, dan batas waktu pengambilan.
        Schema::create('menu_aktifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_makanan_id')->constrained('master_makanans')->cascadeOnDelete();
            $table->foreignId('unit_bisnis_id')->constrained('unit_bisnis_profiles')->cascadeOnDelete();
            // true = makanan digratisin, harga_jual otomatis 0
            $table->boolean('is_gratis')->default(false);
            // harga jual aktual (bisa 0 jika gratis, tidak mempengaruhi master data)
            $table->decimal('harga_jual', 10, 2)->unsigned()->default(0);
            $table->unsignedInteger('stok_porsi');
            $table->dateTime('batas_pengambilan');
            // 'aktif' | 'habis' | 'ditutup'
            $table->enum('status', ['aktif', 'habis', 'ditutup'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_aktifs');
    }
};