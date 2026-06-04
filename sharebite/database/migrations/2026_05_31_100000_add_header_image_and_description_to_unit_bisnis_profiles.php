<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('unit_bisnis_profiles', function (Blueprint $table) {
            $table->string('header_image', 255)->nullable()->after('foto_bisnis');
            $table->text('deskripsi')->nullable()->after('header_image');
        });
    }

    public function down(): void
    {
        Schema::table('unit_bisnis_profiles', function (Blueprint $table) {
            $table->dropColumn(['header_image', 'deskripsi']);
        });
    }
};
