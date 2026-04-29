<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('master_makanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_bisnis_id')->constrained('unit_bisnis_profiles')->cascadeOnDelete();
            $table->string('nama_makanan', 100);
            $table->string('kategori', 50);
            $table->text('deskripsi')->nullable();
            // harga asli / harga normal (tidak berubah walau menu digratisin)
            $table->decimal('harga', 10, 2)->unsigned()->default(0);
            // berat per porsi dalam kg
            $table->decimal('berat', 6, 2)->unsigned();
            // path foto (disimpan di storage, bukan blob)
            $table->string('foto', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_makanans');
    }
};