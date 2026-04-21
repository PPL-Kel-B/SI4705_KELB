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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id('TransaksiID');
            $table->binary('QRcode')->nullable();
            $table->string('Status', 100);
            $table->foreignId('IndividuID')->constrained('individus', 'IndividuID')->onDelete('cascade');
            $table->foreignId('KomunitasID')->constrained('komunitas', 'KomunitasID')->onDelete('cascade');
            $table->foreignId('PesananID')->constrained('pesanans', 'PesananID')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
