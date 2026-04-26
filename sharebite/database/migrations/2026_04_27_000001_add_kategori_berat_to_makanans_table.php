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
        Schema::table('makanans', function (Blueprint $table) {
            $table->string('Kategori', 50)->nullable()->after('Nama_Makanan');
            $table->decimal('Berat', 8, 2)->nullable()->after('Kategori'); // berat dalam kg
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('makanans', function (Blueprint $table) {
            $table->dropColumn(['Kategori', 'Berat']);
        });
    }
};
