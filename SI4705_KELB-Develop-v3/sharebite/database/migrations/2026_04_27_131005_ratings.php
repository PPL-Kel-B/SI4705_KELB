<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            // user yang memberi rating (role: individu atau komunitas)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('unit_bisnis_id')->constrained('unit_bisnis_profiles')->cascadeOnDelete();
            // nilai rating 1-5
            $table->tinyInteger('nilai')->unsigned();
            $table->string('komentar', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};