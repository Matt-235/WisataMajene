<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        
        // SOLUSI: Hitung kategori unik dengan benar
        $totalKategori = $this->hitungKategoriUnik();
        
        // Statistik untuk ditampilkan
        $statistik = [
            'total_wisata' => Wisata::where('status', 'aktif')->count(),
            'total_kategori' => $totalKategori,
        ];
            
        return view('home', compact('wisataPopuler', 'wisataPantai', 'wisataAlam', 'statistik'));
    }
    
    /**
     * Menghitung jumlah kategori unik dari wisata aktif
     */
    private function hitungKategoriUnik()
    {
        // Ambil semua kategori dari wisata aktif
        $allCategories = Wisata::where('status', 'aktif')
            ->pluck('kategori')
            ->filter() // Hapus nilai null atau string kosong
            ->flatMap(function ($kategori) {
                // Pisahkan kategori berdasarkan koma, titik koma, atau pemisah lainnya
                // Gunakan preg_split untuk menangani berbagai format pemisah
                $splitCategories = preg_split('/[,;|]+/', $kategori);
                
                // Bersihkan setiap kategori (trim spasi, ubah ke lowercase untuk konsistensi)
                return array_map(function ($cat) {
                    return strtolower(trim($cat));
                }, $splitCategories);
            })
            ->unique() // Ambil nilai unik
            ->values() // Reset indeks array
            ->count();
        
        return $allCategories;
    }
    
    /**
     * Alternatif solusi dengan query database murni (jika data kategori banyak)
     */
    private function hitungKategoriUnikAlternatif()
    {
        // Jika menggunakan MySQL dan kategori dipisah koma
        // Query ini akan memisahkan kategori dan menghitung yang unik
        $result = DB::select("
            SELECT COUNT(DISTINCT TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(t.kategori, ',', n.n), ',', -1))) as total
            FROM (
                SELECT kategori 
                FROM wisata 
                WHERE status = 'aktif' AND kategori IS NOT NULL AND kategori != ''
            ) t
            CROSS JOIN (
                SELECT 1 n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
                UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8
            ) n
            WHERE CHAR_LENGTH(t.kategori) - CHAR_LENGTH(REPLACE(t.kategori, ',', '')) >= n.n - 1
        ");
        
        return $result[0]->total ?? 0;
    }
    
    /**
     * Solusi sederhana dengan Collection (untuk data tidak terlalu besar)
     */
    private function hitungKategoriUnikSimple()
    {
        $allWisata = Wisata::where('status', 'aktif')->get(['kategori']);
        $uniqueCategories = collect();
        
        foreach ($allWisata as $wisata) {
            if (!empty($wisata->kategori)) {
                // Pisahkan kategori berdasarkan berbagai pemisah
                $categories = preg_split('/[,;|]+/', $wisata->kategori);
                
                foreach ($categories as $cat) {
                    $cat = trim($cat);
                    if (!empty($cat)) {
                        $uniqueCategories->push(strtolower($cat));
                    }
                }
            }
        }
        
        return $uniqueCategories->unique()->count();
    }
}