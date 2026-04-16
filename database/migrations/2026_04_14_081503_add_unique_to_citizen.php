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
        Schema::table('citizen', function (Blueprint $table) {
            $table->unique('id_simpeg', 'idx_unique_simpeg_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citizen', function (Blueprint $table) {
            $table->dropUnique('idx_unique_simpeg_id');
        });
    }
};
