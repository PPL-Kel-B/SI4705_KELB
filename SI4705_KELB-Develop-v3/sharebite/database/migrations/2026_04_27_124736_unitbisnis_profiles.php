<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('unit_bisnis_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_usaha', 100);
            $table->string('jenis_usaha', 100);
            $table->string('alamat', 255);
            $table->string('nib_file', 255)->nullable();
            // 'pending' | 'terverifikasi' | 'ditolak'
            $table->enum('status_verifikasi', ['pending', 'terverifikasi', 'ditolak'])->default('pending');
            // statistik akumulatif
            $table->unsignedInteger('total_makanan_terjual')->default(0);
            $table->decimal('total_berat_terjual', 8, 2)->default(0); // dalam kg
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_bisnis_profiles');
    }
};