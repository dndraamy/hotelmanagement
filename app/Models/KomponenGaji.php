<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomponenGaji extends Model
{
    protected $table = 'komponen_gaji';
    protected $primaryKey = 'id_komponen';
    protected $fillable = ['nama_komponen', 'jenis', 'nominal'];

    public function detailPenggajian()
    {
        return $this->hasMany(DetailPenggajian::class, 'id_komponen', 'id_komponen');
    }
}