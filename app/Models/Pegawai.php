<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';
    protected $fillable = ['nama_lengkap', 'kontak', 'alamat', 'id_divisi', 'id_jabatan'];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'id_divisi', 'id_divisi');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id_pegawai', 'id_pegawai');
    }

    public function jadwalPegawai()
    {
        return $this->hasMany(JadwalPegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'id_pegawai', 'id_pegawai');
    }

    public function pengajuanCuti()
    {
        return $this->hasMany(PengajuanCuti::class, 'id_pegawai', 'id_pegawai');
    }

    public function approverCuti()
    {
        return $this->hasMany(PengajuanCuti::class, 'id_approver', 'id_pegawai');
    }

    public function penggajian()
    {
        return $this->hasMany(Penggajian::class, 'id_pegawai', 'id_pegawai');
    }
}