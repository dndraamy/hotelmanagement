<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiKas extends Model
{
    protected $table = 'transaksi_kas';
    protected $primaryKey = 'id_transaksi';
    
    protected $fillable = [
        'id_user', 'tipe_transaksi', 'kategori', 'nominal', 
        'tanggal_transaksi', 'keterangan', 'bukti_nota_url'
    ];
}