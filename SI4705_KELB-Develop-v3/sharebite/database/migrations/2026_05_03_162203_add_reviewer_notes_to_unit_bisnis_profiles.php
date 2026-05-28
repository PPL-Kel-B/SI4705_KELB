<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('unit_bisnis_profiles', function (Blueprint $table) {

            if (!Schema::hasColumn('unit_bisnis_profiles', 'foto_profile')) {
                $table->text('reviewer_notes')->nullable();
                $table->string('foto_profile')->nullable();
            }

        });
    }

    public function down(): void
    {
        Schema::table('unit_bisnis_profiles', function (Blueprint $table) {
            $table->dropColumn('foto_profile');
        });
    }
};