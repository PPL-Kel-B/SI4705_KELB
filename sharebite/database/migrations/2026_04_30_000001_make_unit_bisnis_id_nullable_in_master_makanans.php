<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat unit_bisnis_id nullable agar bisa insert data
     * tanpa harus ada unit_bisnis_profile terkait.
     */
    public function up(): void
    {
        Schema::table('master_makanans', function (Blueprint $table) {
            // Drop foreign key terlebih dahulu
            $table->dropForeign(['unit_bisnis_id']);
            // Ubah kolom menjadi nullable
            $table->unsignedBigInteger('unit_bisnis_id')->nullable()->change();
            // Tambahkan kembali foreign key dengan nullable
            $table->foreign('unit_bisnis_id')
                  ->references('id')
                  ->on('unit_bisnis_profiles')
                  ->cascadeOnDelete()
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_makanans', function (Blueprint $table) {
            $table->dropForeign(['unit_bisnis_id']);
            $table->unsignedBigInteger('unit_bisnis_id')->nullable(false)->change();
            $table->foreign('unit_bisnis_id')
                  ->references('id')
                  ->on('unit_bisnis_profiles')
                  ->cascadeOnDelete();
        });
    }
};
