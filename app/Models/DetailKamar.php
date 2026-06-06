<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailKamar extends Model
{
    protected $table = 'detail_kamar';
    protected $primaryKey = 'id_detail';
    protected $fillable = ['id_reservasi', 'id_kamar'];

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi', 'id_reservasi');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar');
    }
}