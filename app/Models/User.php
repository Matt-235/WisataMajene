<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'status_verifikasi',
        'no_telepon',
        'alamat'
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

    // Relasi: User sebagai pengelola wisata
    public function wisataDikelola()
    {
        return $this->hasMany(Wisata::class, 'pengelola_id');
    }

    // Relasi: User sebagai wisatawan yang melakukan booking
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'wisatawan_id');
    }

    // Relasi: User sebagai wisatawan yang memberikan ulasan
    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'wisatawan_id');
    }

    // Scope untuk role
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopePengelola($query)
    {
        return $query->where('role', 'pengelola_wisata');
    }

    public function scopeWisatawan($query)
    {
        return $query->where('role', 'wisatawan');
    }
}