<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * - Ubah Foto dari binary -> string (agar bisa simpan path file)
     * - Jadikan UnitBisnisID nullable (agar form create bisa dijalankan tanpa login)
     */
    public function up(): void
    {
        Schema::table('makanans', function (Blueprint $table) {
            // Ubah kolom Foto dari binary ke string (path file)
            $table->string('Foto', 255)->nullable()->change();

            // Jadikan UnitBisnisID nullable (drop FK dulu, lalu buat ulang sebagai nullable)
            $table->unsignedBigInteger('UnitBisnisID')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('makanans', function (Blueprint $table) {
            $table->binary('Foto')->nullable()->change();
            $table->unsignedBigInteger('UnitBisnisID')->nullable(false)->change();
        });
    }
};
