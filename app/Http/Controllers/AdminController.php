<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wisata;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function dashboard()
    {
        $statistik = [
            'total_users' => User::count(),
            'total_wisata' => Wisata::count(),
            'total_bookings' => Booking::count(),
            'total_pengelola' => User::where('role', 'pengelola_wisata')->count(),
            'pending_verifikasi' => User::where('role', 'pengelola_wisata')
                ->where('status_verifikasi', 'belum_terverifikasi')
                ->count(),
        ];
        
        // Booking terbaru
        $bookings_terbaru = Booking::with(['wisatawan', 'wisata'])
            ->latest()
            ->limit(5)
            ->get();
            
        // Pengguna baru
        $pengguna_baru = User::latest()->limit(5)->get();
        
        return view('admin.dashboard', compact('statistik', 'bookings_terbaru', 'pengguna_baru'));
    }
    
    public function users(Request $request)
    {
        $query = User::query();
        
        if ($request->has('role') && $request->role != 'semua') {
            $query->where('role', $request->role);
        }
        
        if ($request->has('status') && $request->status != 'semua') {
            $query->where('status_verifikasi', $request->status);
        }
        
        $users = $query->latest()->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }
    
    public function verifyUser(User $user)
    {
        DB::transaction(function () use ($user) {
            $user->update(['status_verifikasi' => 'terverifikasi']);
        });
        
        return back()->with('success', 'Akun berhasil diverifikasi!');
    }
    
    public function wisata(Request $request)
    {
        $query = Wisata::with('pengelola');
        
        if ($request->has('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search')) {
            $query->where('nama_wisata', 'like', '%' . $request->search . '%');
        }
        
        $wisata = $query->latest()->paginate(10);
        
        return view('admin.wisata.index', compact('wisata'));
    }
    
    public function bookings(Request $request)
    {
        $query = Booking::with(['wisatawan', 'wisata']);
        
        if ($request->has('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
        }
        
        $bookings = $query->latest()->paginate(10);
        
        return view('admin.bookings.index', compact('bookings'));
    }
    
    public function updateBookingStatus(Booking $booking, Request $request)
    {
        $request->validate([
            'status' => 'required|in:pending,dikonfirmasi,dibatalkan,selesai'
        ]);
        
        DB::transaction(function () use ($booking, $request) {
            $booking->update(['status' => $request->status]);
        });
        
        return back()->with('success', 'Status booking berhasil diperbarui!');
    }

    public function destroyUser(User $user)
{
    // Validasi
    if ($user->id === auth()->id()) {
        return back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
    }
    
    DB::transaction(function () use ($user) {
        // 1. Jika user adalah pengelola, hapus semua wisata miliknya
        if ($user->role === 'pengelola_wisata') {
            $wisataList = $user->wisataDikelola;
            
            foreach ($wisataList as $wisata) {
                // Hapus gambar wisata
                if ($wisata->gambar && Storage::disk('public')->exists('wisata/' . $wisata->gambar)) {
                    Storage::disk('public')->delete('wisata/' . $wisata->gambar);
                }
                
                // Hapus booking terkait wisata ini
                $wisata->bookings()->delete();
                
                // Hapus ulasan terkait wisata ini
                $wisata->ulasan()->delete();
                
                // Hapus wisata
                $wisata->delete();
            }
        }
        
        // 2. Hapus booking yang dibuat user (sebagai wisatawan)
        $user->bookings()->delete();
        
        // 3. Hapus ulasan yang dibuat user
        $user->ulasan()->delete();
        
        // 4. Hapus user
        $user->delete();
    });
    
    return back()->with('success', 'Pengguna berhasil dihapus permanen!');
}

}