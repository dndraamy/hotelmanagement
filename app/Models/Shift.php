<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $table = 'shift';
    protected $primaryKey = 'id_shift';
    protected $fillable = ['nama_shift', 'jam_mulai', 'jam_selesai'];

    public function jadwalPegawai()
    {
        return $this->hasMany(JadwalPegawai::class, 'id_shift', 'id_shift');
    }
}