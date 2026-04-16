<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citizen extends Model
{
    protected $table = "citizen";
    protected $fillable = ['id_simpeg', 'nama', 'nip', 'nik', 'email', 'id_pangkat', 'id_pendidikan', 'tempat_pendidikan', 'tgl_lulus', 'tempat_lahir', 'tanggal_lahir', 'no_hp', 'jenis_kelamin', 'id_jabatan', 'id_bagian', 'satker_id', 'foto', 'masuk_kerja', 'status', 'synced'];
}
