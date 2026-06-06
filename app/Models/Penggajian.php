<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    protected $table = 'penggajian';
    protected $primaryKey = 'id_penggajian';
    protected $fillable = [
        'id_pegawai', 'periode_bulan', 'periode_tahun', 
        'total_gaji_pokok', 'total_uang_lembur', 
        'total_potongan', 'gaji_bersih', 'tanggal_cetak_slip'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function detailPenggajian()
    {
        return $this->hasMany(DetailPenggajian::class, 'id_penggajian', 'id_penggajian');
    }
}