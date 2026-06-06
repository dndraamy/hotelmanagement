<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemMenu extends Model
{
    protected $table = 'item_menu';
    protected $primaryKey = 'id_item';
    protected $fillable = ['nama_item', 'kategori', 'harga'];

    public function detailPesananRestoran()
    {
        return $this->hasMany(DetailPesananRestoran::class, 'id_item', 'id_item');
    }
}