<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    use HasFactory;

    protected $table = 'wisata';
    
    protected $fillable = [
        'nama_wisata',
        'deskripsi',
        'lokasi',
        'latitude',
        'longitude',
        'kategori',
        'harga_tiket',
        'kapasitas_harian',
        'gambar',
        'status',
        'pengelola_id'
    ];

    // Relasi: Wisata dimiliki oleh pengelola
    public function pengelola()
    {
        return $this->belongsTo(User::class, 'pengelola_id');
    }

    // Relasi: Wisata memiliki banyak booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Relasi: Wisata memiliki banyak ulasan
    public function ulasan()
    {
        return $this->hasMany(Ulasan::class);
    }

    // Hitung rata-rata rating
    public function getRatingAttribute()
    {
        return $this->ulasan()->avg('rating') ?? 0;
    }

    // Scope aktif - PERBAIKAN DI SINI
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope berdasarkan kategori
    public function scopeByKategori($query, $kategori)
    {
        if ($kategori && $kategori !== 'semua') {
            return $query->where('kategori', $kategori);
        }
        return $query;
    }

    // Scope search
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('nama_wisata', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    // Scope harga minimal
    public function scopeHargaMin($query, $hargaMin)
    {
        if ($hargaMin) {
            return $query->where('harga_tiket', '>=', (float) $hargaMin);
        }
        return $query;
    }

    // Scope harga maksimal
    public function scopeHargaMax($query, $hargaMax)
    {
        if ($hargaMax) {
            return $query->where('harga_tiket', '<=', (float) $hargaMax);
        }
        return $query;
    }
}