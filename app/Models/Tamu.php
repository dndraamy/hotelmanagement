<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tamu extends Model
{
    protected $table = 'tamu';
    protected $primaryKey = 'id_tamu';
    protected $fillable = ['tipe_identitas', 'no_identitas', 'nama_lengkap', 'kontak'];

    public function reservasi()
    {
        return $this->hasMany(Reservasi::class, 'id_tamu', 'id_tamu');
    }
}