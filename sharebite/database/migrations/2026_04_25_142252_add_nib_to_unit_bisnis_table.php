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
        Schema::table('unit_bisnis', function (Blueprint $table) {
            $table->string('NIB_File')->nullable();
            $table->string('Latitude')->nullable();
            $table->string('Longitude')->nullable();
            $table->unsignedBigInteger('AdminID')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unit_bisnis', function (Blueprint $table) {
            $table->dropColumn(['NIB_File', 'Latitude', 'Longitude']);
            $table->unsignedBigInteger('AdminID')->nullable(false)->change();
        });
    }
};
