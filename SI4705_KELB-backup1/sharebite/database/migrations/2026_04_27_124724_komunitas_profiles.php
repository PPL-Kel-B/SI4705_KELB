<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('komunitas_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_komunitas', 100);
            $table->integer('jumlah_anggota')->default(0);
            // statistik akumulatif
            $table->decimal('total_berat_diselamatkan', 8, 2)->default(0); // dalam kg
            $table->unsignedInteger('total_makanan_dibeli')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komunitas_profiles');
    }
};