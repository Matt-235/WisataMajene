<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use Illuminate\Http\Request;

class WisataController extends Controller
{
    public function index(Request $request)
    {
        $query = Wisata::where('status', 'aktif');
        
        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_wisata', 'like', '%' . $search . '%')
                  ->orWhere('lokasi', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }
        
        // Filter kategori - PERBAIKAN DI SINI
        if ($request->filled('kategori') && $request->kategori != 'semua') {
            $query->where('kategori', $request->kategori);
        }
        
        // Filter harga minimal
        if ($request->filled('harga_min')) {
            $query->where('harga_tiket', '>=', (float) $request->harga_min);
        }
        
        // Filter harga maksimal
        if ($request->filled('harga_max')) {
            $query->where('harga_tiket', '<=', (float) $request->harga_max);
        }
        
        // Urutkan berdasarkan yang terbaru
        $query->orderBy('created_at', 'desc');
        
        // Ambil data dengan pagination
        $wisata = $query->paginate(12);
        
        // Ambil kategori unik untuk dropdown filter
        $kategoriList = Wisata::select('kategori')
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->orderBy('kategori')
            ->get()
            ->pluck('kategori');
        
        return view('wisata.index', compact('wisata', 'kategoriList'));
    }
    
    public function show(Wisata $wisata)
    {
        // Pastikan wisata aktif
        if ($wisata->status !== 'aktif') {
            abort(404);
        }
        
        // Ambil ulasan untuk wisata ini
        $ulasan = $wisata->ulasan()
            ->with('wisatawan')
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        
        // Ambil rekomendasi wisata (dalam kategori yang sama)
        $rekomendasi = Wisata::where('status', 'aktif')
            ->where('kategori', $wisata->kategori)
            ->where('id', '!=', $wisata->id)
            ->inRandomOrder()
            ->limit(3)
            ->get();
            
        return view('wisata.show', compact('wisata', 'ulasan', 'rekomendasi'));
    }
}