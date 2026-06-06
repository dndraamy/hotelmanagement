<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    protected $table = 'divisi';
    protected $primaryKey = 'id_divisi';
    protected $fillable = ['nama_divisi'];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'id_divisi', 'id_divisi');
    }
}