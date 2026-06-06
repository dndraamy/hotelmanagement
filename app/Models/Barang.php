<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    protected $fillable = ['nama_barang', 'kategori', 'satuan', 'stok_sekarang', 'stok_minimal'];

    public function mutasiStok()
    {
        return $this->hasMany(MutasiStok::class, 'id_barang', 'id_barang');
    }
}