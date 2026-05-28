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
        $profiles = DB::table('unit_bisnis_profiles')
            ->whereNotNull('alamat')
            ->where('alamat', '!=', '')
            ->get();

        foreach ($profiles as $profile) {
            DB::table('users')
                ->where('id', $profile->user_id)
                ->where(function ($query) {
                    $query->whereNull('alamat')->orWhere('alamat', '');
                })
                ->update(['alamat' => $profile->alamat]);
        }

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
