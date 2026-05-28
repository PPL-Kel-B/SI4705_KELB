<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            if (!Schema::hasColumn('ratings', 'pesanan_id')) {
                $table->foreignId('pesanan_id')->nullable()->constrained('pesanans')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('ratings', 'skor_rating')) {
                $table->tinyInteger('skor_rating')->unsigned()->nullable();
            }
            if (!Schema::hasColumn('ratings', 'catatan_pengalaman')) {
                $table->string('catatan_pengalaman', 255)->nullable();
            }
            if (!Schema::hasColumn('ratings', 'foto_bukti_berbagi')) {
                $table->string('foto_bukti_berbagi', 255)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            if (Schema::hasColumn('ratings', 'pesanan_id')) {
                $table->dropForeign(['pesanan_id']);
                $table->dropColumn('pesanan_id');
            }
            if (Schema::hasColumn('ratings', 'skor_rating')) {
                $table->dropColumn('skor_rating');
            }
            if (Schema::hasColumn('ratings', 'catatan_pengalaman')) {
                $table->dropColumn('catatan_pengalaman');
            }
            if (Schema::hasColumn('ratings', 'foto_bukti_berbagi')) {
                $table->dropColumn('foto_bukti_berbagi');
            }
        });
    }
};
