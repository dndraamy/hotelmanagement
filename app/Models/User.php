<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'id_pegawai',
        'username',
        'email',
        'email_verified_at',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    // Role dikelola oleh Spatie Permission
    // Gunakan: $user->assignRole('Resepsionis'), $user->hasRole('Manajer Hotel')
    // Di Blade: @role('Staf HRD') ... @endrole

    public function transaksiKas()
    {
        return $this->hasMany(TransaksiKas::class, 'id_user', 'id_user');
    }
}