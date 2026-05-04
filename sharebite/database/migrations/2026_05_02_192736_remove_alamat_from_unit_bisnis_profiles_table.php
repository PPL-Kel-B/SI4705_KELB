<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pindahkan data alamat ke tabel users jika di users masih kosong
        DB::statement("UPDATE users 
                       JOIN unit_bisnis_profiles ON users.id = unit_bisnis_profiles.user_id 
                       SET users.alamat = unit_bisnis_profiles.alamat 
                       WHERE users.alamat IS NULL OR users.alamat = ''");

        Schema::table('unit_bisnis_profiles', function (Blueprint $table) {
            $table->dropColumn('alamat');
        });
    }

    public function down(): void
    {
        Schema::table('unit_bisnis_profiles', function (Blueprint $table) {
            $table->string('alamat', 255)->nullable();
        });
    }
};
