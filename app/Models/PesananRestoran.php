<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesananRestoran extends Model
{
    protected $table = 'pesanan_restoran';
    protected $primaryKey = 'id_pesanan';
    protected $fillable = ['id_reservasi', 'tanggal_pesanan', 'total_harga', 'status_pembayaran', 'status_pesanan'];

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi', 'id_reservasi');
    }

    public function detailPesananRestoran()
    {
        return $this->hasMany(DetailPesananRestoran::class, 'id_pesanan', 'id_pesanan');
    }
}