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
        Schema::table('komunitas_profiles', function (Blueprint $table) {
            $table->string('penanggung_jawab')->after('nama_komunitas')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komunitas_profiles', function (Blueprint $table) {
            $table->dropColumn('penanggung_jawab');
        });
    }
};
