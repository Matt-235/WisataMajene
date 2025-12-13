<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 6 wisata aktif untuk rekomendasi
        $wisataPopuler = Wisata::where('status', 'aktif')
            ->withCount('ulasan')
            ->withAvg('ulasan', 'rating')
            ->orderByDesc('ulasan_avg_rating')
            ->orderByDesc('ulasan_count')
            ->limit(6)
            ->get();
            
        // Kategori untuk section khusus
        $kategoriPantai = ['Pantai', 'Pesisir'];
        $kategoriAlam = ['Air Terjun', 'Gunung', 'Bukit', 'Hutan'];
        
        // Ambil wisata pantai
        $wisataPantai = Wisata::where('status', 'aktif')
            ->where(function($query) use ($kategoriPantai) {
                foreach ($kategoriPantai as $kategori) {
                    $query->orWhere('kategori', 'like', '%' . $kategori . '%');
                }
            })
            ->limit(3)
            ->get();
            
        // Ambil wisata alam
        $wisataAlam = Wisata::where('status', 'aktif')
            ->where(function($query) use ($kategoriAlam) {
                foreach ($kategoriAlam as $kategori) {
                    $query->orWhere('kategori', 'like', '%' . $kategori . '%');
                }
            })
            ->limit(3)
            ->get();
            
        // Statistik untuk ditampilkan
        $statistik = [
            'total_wisata' => Wisata::where('status', 'aktif')->count(),
            'total_kategori' => Wisata::select('kategori')->distinct()->count(),
        ];
            
        return view('home', compact('wisataPopuler', 'wisataPantai', 'wisataAlam', 'statistik'));
    }
}