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
        Schema::create('makanans', function (Blueprint $table) {
            $table->id('MakananID');
            $table->string('Nama_Makanan', 100);
            $table->integer('Jumlah_porsi');
            $table->date('Batas_waktu_pengambilan');
            $table->double('Harga');
            $table->binary('Foto')->nullable();
            $table->string('Status', 20);
            $table->foreignId('UnitBisnisID')->constrained('unit_bisnis', 'UnitBisnisID')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('makanans');
    }
};
