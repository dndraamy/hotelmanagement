<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenggajian extends Model
{
    protected $table = 'detail_penggajian';
    protected $primaryKey = 'id_detail_penggajian';
    protected $fillable = ['id_penggajian', 'id_komponen', 'nominal_terapan'];

    public function penggajian()
    {
        return $this->belongsTo(Penggajian::class, 'id_penggajian', 'id_penggajian');
    }

    public function komponen()
    {
        return $this->belongsTo(KomponenGaji::class, 'id_komponen', 'id_komponen');
    }
}