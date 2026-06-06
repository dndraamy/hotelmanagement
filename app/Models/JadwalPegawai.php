<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPegawai extends Model
{
    protected $table = 'jadwal_pegawai';
    protected $primaryKey = 'id_jadwal';
    protected $fillable = ['id_pegawai', 'id_shift', 'tanggal'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'id_shift', 'id_shift');
    }
}