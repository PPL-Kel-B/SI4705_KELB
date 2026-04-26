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
        Schema::create('active_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('makanans', 'MakananID')->onDelete('cascade');
            $table->foreignId('unit_bisnis_id', 'UnitBisnisID')->constrained('unit_bisnis', 'UnitBisnisID')->onDelete('cascade');
            $table->boolean('is_free')->default(false);
            $table->integer('stock')->default(0);
            $table->integer('limit_per_user')->default(1);
            $table->enum('status', ['tersedia', 'habis', 'segera_habis'])->default('tersedia');
            $table->timestamps();
            
            $table->unique(['menu_id', 'unit_bisnis_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('active_menus');
    }
};
