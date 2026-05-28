<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            // pengirim pesan
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            // penerima pesan
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $table->text('pesan');
            $table->timestamp('waktu')->useCurrent();
            // tanda sudah dibaca atau belum
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['sender_id', 'receiver_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};