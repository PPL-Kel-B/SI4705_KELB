<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('verifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_bisnis_id')->constrained('unit_bisnis_profiles')->cascadeOnDelete();
            // admin yang memverifikasi, nullable karena bisa belum diproses
            $table->foreignId('admin_id')->nullable()->constrained('admin_profiles')->nullOnDelete();
            $table->string('dokumen', 255);
            // 'pending' | 'disetujui' | 'ditolak'
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            // catatan penolakan dari admin (opsional)
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifikasis');
    }
};