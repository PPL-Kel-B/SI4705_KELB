<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->string('no_hp', 20);
            // role: 'admin' | 'individu' | 'komunitas' | 'unit_bisnis'
            $table->enum('role', ['admin', 'individu', 'komunitas', 'unit_bisnis']);
            $table->string('alamat', 255)->nullable();
            $table->string('foto_profil', 255)->nullable();
            // koordinat lokasi user (untuk kalkulasi jarak ke unit bisnis)
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};