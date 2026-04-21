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
        Schema::create('komunitas', function (Blueprint $table) {
            $table->id('KomunitasID');
            $table->string('Nama', 100);
            $table->string('Nama_penanggung_jawab', 100);
            $table->integer('Jumlah_anggota');
            $table->string('Nomor_hp', 20);
            $table->string('Email', 45)->unique();
            $table->string('Password', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komunitas');
    }
};
