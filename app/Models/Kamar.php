<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table      = 'kamar';
    protected $primaryKey = 'id_kamar';

    protected $fillable = [
        'nomor_kamar',
        'lantai',
        'id_tipe',
        'status_kamar',
    ];

    public function tipeKamar()
    {
        return $this->belongsTo(TipeKamar::class, 'id_tipe', 'id_tipe');
    }

    public function detailKamar()
    {
        return $this->hasMany(DetailKamar::class, 'id_kamar', 'id_kamar');
    }
}