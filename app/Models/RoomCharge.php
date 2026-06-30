<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomCharge extends Model
{
    protected $table      = 'room_charges';
    protected $primaryKey = 'id_room_charge';

    protected $fillable = [
        'id_reservasi',
        'deskripsi',
        'nominal',
        'kategori',
        'catatan',
        'charged_by',
        'status',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
    ];

    /** Reservasi terkait */
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi', 'id_reservasi');
    }
}
