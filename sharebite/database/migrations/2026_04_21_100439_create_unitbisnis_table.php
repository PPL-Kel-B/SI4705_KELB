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
        Schema::create('unit_bisnis', function (Blueprint $table) {
            $table->id('UnitBisnisID');
            $table->string('Nama_Usaha', 100);
            $table->string('Jenis_Usaha', 100);
            $table->string('Alamat', 100);
            $table->string('Nomor_hp', 20);
            $table->string('Email', 45)->unique();
            $table->string('Password', 100);
            $table->foreignId('AdminID')->constrained('admins', 'AdminID')->onDelete('cascade');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unitbisnis');
    }
};
