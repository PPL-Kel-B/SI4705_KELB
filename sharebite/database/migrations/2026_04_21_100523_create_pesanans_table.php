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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id('PesananID');
            $table->integer('Jumlah_porsi');
            $table->double('Total_Harga');
            $table->string('Status', 20);
            $table->timestamp('Waktu_pesan');
            $table->date('Waktu_diambil')->nullable();
            $table->foreignId('MakananID')->constrained('makanans', 'MakananID')->onDelete('cascade');
            $table->foreignId('UnitBisnisID')->constrained('unit_bisnis', 'UnitBisnisID')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
