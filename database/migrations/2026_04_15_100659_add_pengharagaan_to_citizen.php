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
            $table->text('penghargaan')->after('masuk_kerja')->nullable();
            $table->text('riwayat_jabatan')->after('penghargaan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citizen', function (Blueprint $table) {
            //
        });
    }
};
