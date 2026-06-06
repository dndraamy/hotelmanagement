<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiStok extends Model
{
    protected $table = 'mutasi_stok';
    protected $primaryKey = 'id_mutasi';
    protected $fillable = ['id_barang', 'id_supplier', 'jenis_mutasi', 'jumlah', 'tanggal_mutasi', 'keterangan'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
    }
}