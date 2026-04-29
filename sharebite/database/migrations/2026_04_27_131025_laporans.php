<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admin_profiles')->cascadeOnDelete();
            $table->text('deskripsi')->nullable();
            // 'draft' | 'final'
            $table->enum('status', ['draft', 'final'])->default('draft');
            // contoh format: '2026-Q1', '2026-04'
            $table->string('periode', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};