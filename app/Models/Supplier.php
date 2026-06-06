<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'id_supplier';
    protected $fillable = ['nama_supplier', 'kontak', 'alamat'];

    public function mutasiStok()
    {
        return $this->hasMany(MutasiStok::class, 'id_supplier', 'id_supplier');
    }
}