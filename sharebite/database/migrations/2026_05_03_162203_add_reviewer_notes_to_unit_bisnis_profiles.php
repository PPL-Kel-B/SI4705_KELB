<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('unit_bisnis_profiles', function (Blueprint $table) {
<<<<<<< HEAD

            if (!Schema::hasColumn('unit_bisnis_profiles', 'foto_profile')) {
                $table->text('reviewer_notes')->nullable();
                $table->string('foto_profile')->nullable();
            }

=======
            if (!Schema::hasColumn('unit_bisnis_profiles', 'reviewer_notes')) {
                $table->text('reviewer_notes')->nullable()->after('status_verifikasi');
            }
>>>>>>> origin/Develop-v3
        });
    }

    public function down(): void
    {
        Schema::table('unit_bisnis_profiles', function (Blueprint $table) {
<<<<<<< HEAD
            $table->dropColumn('foto_profile');
=======
            $table->dropColumn('reviewer_notes');
>>>>>>> origin/Develop-v3
        });
    }
};