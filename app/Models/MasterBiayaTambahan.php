<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterBiayaTambahan extends Model
{
    protected $table = 'master_biaya_tambahan';
    protected $primaryKey = 'id_biaya';
    protected $fillable = ['nama_biaya', 'nominal_default'];

    public function tagihanTambahan()
    {
        return $this->hasMany(TagihanTambahan::class, 'id_biaya', 'id_biaya');
    }
}