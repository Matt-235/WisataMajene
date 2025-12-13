<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use App\Models\Booking;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WisatawanController extends Controller
{
    public function bookings()
    {
        $bookings = Auth::user()->bookings()->with('wisata')->latest()->paginate(10);
        return view('wisatawan.bookings.index', compact('bookings'));
    }
    
    public function createBooking(Wisata $wisata)
    {
        return view('wisatawan.bookings.create', compact('wisata'));
    }
    
    public function storeBooking(Request $request, Wisata $wisata)
    {
        $request->validate([
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
            'jumlah_tiket' => 'required|integer|min:1|max:10',
            'catatan' => 'nullable|string|max:500',
        ]);
        
        // Cek ketersediaan tiket untuk tanggal tersebut
        $totalBookingHariIni = Booking::where('wisata_id', $wisata->id)
            ->where('tanggal_kunjungan', $request->tanggal_kunjungan)
            ->whereIn('status', ['pending', 'dikonfirmasi'])
            ->sum('jumlah_tiket');
            
        $sisaKuota = $wisata->kapasitas_harian - $totalBookingHariIni;
        
        if ($request->jumlah_tiket > $sisaKuota) {
            return back()->withErrors([
                'jumlah_tiket' => 'Kuota tersisa untuk tanggal tersebut hanya ' . $sisaKuota . ' tiket.'
            ]);
        }
        
        $booking = null;
        
        DB::transaction(function () use ($request, $wisata, &$booking) {
            $total_harga = $wisata->harga_tiket * $request->jumlah_tiket;
            
            $booking = Booking::create([
                'wisatawan_id' => Auth::id(),
                'wisata_id' => $wisata->id,
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'jumlah_tiket' => $request->jumlah_tiket,
                'total_harga' => $total_harga,
                'catatan' => $request->catatan,
                'status' => 'pending',
            ]);
        });
        
        return redirect()->route('wisatawan.bookings')
            ->with('success', 'Booking berhasil dibuat! Kode booking: ' . $booking->kode_booking);
    }
    
    public function cancelBooking(Booking $booking)
    {
        // Pastikan booking milik wisatawan yang login
        if ($booking->wisatawan_id !== Auth::id()) {
            abort(403);
        }
        
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Hanya booking dengan status pending yang dapat dibatalkan.');
        }
        
        DB::transaction(function () use ($booking) {
            $booking->update(['status' => 'dibatalkan']);
        });
        
        return back()->with('success', 'Booking berhasil dibatalkan!');
    }
    
    public function createUlasan(Booking $booking)
    {
        // Pastikan booking milik wisatawan yang login dan sudah selesai
        if ($booking->wisatawan_id !== Auth::id() || $booking->status !== 'selesai') {
            abort(403);
        }
        
        // Cek apakah sudah memberikan ulasan
        $existingUlasan = Ulasan::where('wisatawan_id', Auth::id())
            ->where('wisata_id', $booking->wisata_id)
            ->first();
            
        if ($existingUlasan) {
            return redirect()->route('wisata.show', $booking->wisata_id)
                ->with('info', 'Anda sudah memberikan ulasan untuk wisata ini.');
        }
        
        return view('wisatawan.ulasan.create', compact('booking'));
    }
    
    public function storeUlasan(Request $request, Booking $booking)
    {
        // Pastikan booking milik wisatawan yang login dan sudah selesai
        if ($booking->wisatawan_id !== Auth::id() || $booking->status !== 'selesai') {
            abort(403);
        }
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:1000',
        ]);
        
        DB::transaction(function () use ($request, $booking) {
            Ulasan::create([
                'wisatawan_id' => Auth::id(),
                'wisata_id' => $booking->wisata_id,
                'rating' => $request->rating,
                'komentar' => $request->komentar,
            ]);
        });
        
        return redirect()->route('wisata.show', $booking->wisata_id)
            ->with('success', 'Ulasan berhasil ditambahkan!');
    }
}