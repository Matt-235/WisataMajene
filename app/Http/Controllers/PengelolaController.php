<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengelolaController extends Controller
{
    
    public function dashboard()
    {
        $user = Auth::user();
        
        $statistik = [
            'total_wisata' => $user->wisataDikelola()->count(),
            'total_bookings' => Booking::whereHas('wisata', function($query) use ($user) {
                $query->where('pengelola_id', $user->id);
            })->count(),
            'pending_bookings' => Booking::whereHas('wisata', function($query) use ($user) {
                $query->where('pengelola_id', $user->id);
            })->where('status', 'pending')->count(),
        ];
        
        $bookings_terbaru = Booking::whereHas('wisata', function($query) use ($user) {
                $query->where('pengelola_id', $user->id);
            })
            ->with('wisatawan')
            ->latest()
            ->limit(5)
            ->get();
            
        return view('pengelola.dashboard', compact('statistik', 'bookings_terbaru'));
    }
    
    
    public function wisata()
    {
        $wisata = Auth::user()->wisataDikelola()->latest()->paginate(10);
        return view('pengelola.wisata.index', compact('wisata'));
    }
    
    public function createWisata()
    {
        return view('pengelola.wisata.create');
    }
    
    public function storeWisata(Request $request)
    {
        $request->validate([
            'nama_wisata' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'kategori' => 'required|string|max:100',
            'harga_tiket' => 'required|numeric|min:0',
            'kapasitas_harian' => 'required|integer|min:1',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        
        DB::transaction(function () use ($request) {
            $data = $request->all();
            $data['pengelola_id'] = Auth::id();
            
            if ($request->hasFile('gambar')) {
                // Generate unique filename
                $filename = time() . '_' . str_replace(' ', '_', $request->gambar->getClientOriginalName());
                
                // Store file in public disk under wisata directory
                $path = $request->gambar->storeAs('wisata', $filename, 'public');
                $data['gambar'] = $filename;
            }
            
            Wisata::create($data);
        });
        
        return redirect()->route('pengelola.wisata')->with('success', 'Wisata berhasil ditambahkan!');
    }
    
    public function editWisata(Wisata $wisata)
    {
        // Pastikan wisata ini milik pengelola yang login
        if ($wisata->pengelola_id !== Auth::id()) {
            abort(403);
        }
        
        return view('pengelola.wisata.edit', compact('wisata'));
    }
    
    public function updateWisata(Request $request, Wisata $wisata)
    {
        // Pastikan wisata ini milik pengelola yang login
        if ($wisata->pengelola_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'nama_wisata' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'kategori' => 'required|string|max:100',
            'harga_tiket' => 'required|numeric|min:0',
            'kapasitas_harian' => 'required|integer|min:1',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:aktif,nonaktif',
        ]);
        
        DB::transaction(function () use ($request, $wisata) {
            $data = $request->except('hapus_gambar');
            
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama jika ada
                if ($wisata->gambar && Storage::disk('public')->exists('wisata/' . $wisata->gambar)) {
                    Storage::disk('public')->delete('wisata/' . $wisata->gambar);
                }
                
                // Upload gambar baru
                $filename = time() . '_' . str_replace(' ', '_', $request->gambar->getClientOriginalName());
                $path = $request->gambar->storeAs('wisata', $filename, 'public');
                $data['gambar'] = $filename;
            } elseif ($request->has('hapus_gambar') && $request->hapus_gambar == 'on') {
                // Hapus gambar jika checkbox dicentang
                if ($wisata->gambar && Storage::disk('public')->exists('wisata/' . $wisata->gambar)) {
                    Storage::disk('public')->delete('wisata/' . $wisata->gambar);
                }
                $data['gambar'] = null;
            }
            
            $wisata->update($data);
        });
        
        return redirect()->route('pengelola.wisata')->with('success', 'Wisata berhasil diperbarui!');
    }
    
    public function destroyWisata(Wisata $wisata)
    {
        // Pastikan wisata ini milik pengelola yang login
        if ($wisata->pengelola_id !== Auth::id()) {
            abort(403);
        }
        
        DB::transaction(function () use ($wisata) {
            // Hapus gambar jika ada
            if ($wisata->gambar && Storage::disk('public')->exists('wisata/' . $wisata->gambar)) {
                Storage::disk('public')->delete('wisata/' . $wisata->gambar);
            }
            
            $wisata->delete();
        });
        
        return redirect()->route('pengelola.wisata')->with('success', 'Wisata berhasil dihapus!');
    }
    
    public function bookings()
    {
        $user = Auth::user();
        $bookings = Booking::whereHas('wisata', function($query) use ($user) {
                $query->where('pengelola_id', $user->id);
            })
            ->with(['wisatawan', 'wisata'])
            ->latest()
            ->paginate(10);
            
        return view('pengelola.bookings.index', compact('bookings'));
    }
    
    public function updateBookingStatus(Booking $booking, Request $request)
    {
        // Pastikan booking untuk wisata milik pengelola yang login
        if ($booking->wisata->pengelola_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'status' => 'required|in:dikonfirmasi,dibatalkan,selesai'
        ]);
        
        DB::transaction(function () use ($booking, $request) {
            $booking->update(['status' => $request->status]);
        });
        
        return back()->with('success', 'Status booking berhasil diperbarui!');
    }
}