<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesananRestoran extends Model
{
    protected $table = 'detail_pesanan_restoran';
    protected $primaryKey = 'id_detail';
    protected $fillable = ['id_pesanan', 'id_item', 'qty', 'subtotal'];

    public function pesananRestoran()
    {
        return $this->belongsTo(PesananRestoran::class, 'id_pesanan', 'id_pesanan');
    }

    public function itemMenu()
    {
        return $this->belongsTo(ItemMenu::class, 'id_item', 'id_item');
    }
}