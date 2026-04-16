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
        Schema::create('citizen', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nip');
            $table->string('nik');
            $table->string('email');
            $table->integer('id_pangkat');
            $table->integer('id_pendidikan');
            $table->string('tempat_pendidikan')->nullable();
            $table->date('tgl_lulus')->nullable();
            $table->string('tempat_lahir');
            $table->string('no_hp');
            $table->integer('jenis_kelamin');
            $table->integer('id_jabatan');
            $table->integer('id_bagian');
            $table->integer('satker_id');
            $table->string('foto');
            $table->date('masuk_kerja')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('synced')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citizen');
    }
};
