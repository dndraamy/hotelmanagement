<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagihanTambahan extends Model
{
    protected $table = 'tagihan_tambahan';
    protected $primaryKey = 'id_tagihan_tambahan';
    protected $fillable = ['id_reservasi', 'id_biaya', 'nominal_akhir'];

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi', 'id_reservasi');
    }

    public function masterBiayaTambahan()
    {
        return $this->belongsTo(MasterBiayaTambahan::class, 'id_biaya', 'id_biaya');
    }
}