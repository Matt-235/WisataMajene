<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    protected $casts = [
        'tanggal_kunjungan' => 'date', // Tambahkan ini
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    
    protected $fillable = [
        'kode_booking',
        'wisatawan_id',
        'wisata_id',
        'tanggal_kunjungan',
        'jumlah_tiket',
        'total_harga',
        'status',
        'catatan'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            $booking->kode_booking = 'BK-' . date('Ymd') . '-' . strtoupper(uniqid());
        });
    }

    // Relasi: Booking dimiliki oleh wisatawan
    public function wisatawan()
    {
        return $this->belongsTo(User::class, 'wisatawan_id');
    }

    // Relasi: Booking untuk wisata tertentu
    public function wisata()
    {
        return $this->belongsTo(Wisata::class);
    }

    // Scope berdasarkan status
    public function scopeStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    // Scope berdasarkan wisatawan
    public function scopeByWisatawan($query, $wisatawanId)
    {
        return $query->where('wisatawan_id', $wisatawanId);
    }

    // Scope berdasarkan pengelola
    public function scopeByPengelola($query, $pengelolaId)
    {
        return $query->whereHas('wisata', function($q) use ($pengelolaId) {
            $q->where('pengelola_id', $pengelolaId);
        });
    }
}