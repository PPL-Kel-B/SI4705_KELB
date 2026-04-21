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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id('RatingID');
            $table->integer('Nilai');
            $table->string('Komentar', 100)->nullable();
            $table->string('Bukti', 100)->nullable();
            $table->foreignId('IndividuID')->constrained('individus', 'IndividuID')->onDelete('cascade');
            $table->foreignId('KomunitasID')->constrained('komunitas', 'KomunitasID')->onDelete('cascade');
            $table->foreignId('UnitBisnisID')->constrained('unit_bisnis', 'UnitBisnisID')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
