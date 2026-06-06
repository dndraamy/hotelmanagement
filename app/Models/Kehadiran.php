<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    protected $table = 'kehadiran';
    protected $primaryKey = 'id_kehadiran';
    protected $fillable = ['id_pegawai', 'tanggal', 'jam_masuk', 'jam_pulang', 'status_kehadiran', 'menit_lembur'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }
}