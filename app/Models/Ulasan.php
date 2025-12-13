<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';
    
    protected $fillable = [
        'wisatawan_id',
        'wisata_id',
        'rating',
        'komentar'
    ];

    // Relasi: Ulasan dimiliki oleh wisatawan
    public function wisatawan()
    {
        return $this->belongsTo(User::class, 'wisatawan_id');
    }

    // Relasi: Ulasan untuk wisata tertentu
    public function wisata()
    {
        return $this->belongsTo(Wisata::class);
    }
}