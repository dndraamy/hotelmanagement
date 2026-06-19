<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanCuti extends Model
{
    protected $table = 'pengajuan_cuti';
    protected $primaryKey = 'id_cuti';
    protected $fillable = ['id_pegawai', 'tanggal_mulai', 'tanggal_selesai', 'alasan', 'status_approval', 'id_approver'];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function approver()
    {
        return $this->belongsTo(Pegawai::class, 'id_approver', 'id_pegawai');
    }

    public function getDurasiHariAttribute(): int
    {
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
    }
}