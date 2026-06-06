<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    protected $table = 'reservasi';
    protected $primaryKey = 'id_reservasi';
    protected $fillable = ['id_tamu', 'tanggal_reservasi', 'tgl_checkin', 'tgl_checkout', 'status_reservasi'];

    public function tamu()
    {
        return $this->belongsTo(Tamu::class, 'id_tamu', 'id_tamu');
    }

    public function detailKamar()
    {
        return $this->hasMany(DetailKamar::class, 'id_reservasi', 'id_reservasi');
    }

    public function tagihanTambahan()
    {
        return $this->hasMany(TagihanTambahan::class, 'id_reservasi', 'id_reservasi');
    }

    public function pesananRestoran()
    {
        return $this->hasMany(PesananRestoran::class, 'id_reservasi', 'id_reservasi');
    }

    public function tagihan()
    {
        return $this->hasOne(Tagihan::class, 'id_reservasi', 'id_reservasi');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_reservasi', 'id_reservasi');
    }
}