@extends('layouts.app')

@section('title', ' - Booking Saya')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Booking Saya</h2>
            <p class="text-muted">Riwayat dan status booking destinasi wisata</p>
        </div>
    </div>

    @if($bookings->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada booking</h5>
            <p class="text-muted">Mulai dengan membooking destinasi wisata favorit Anda</p>
            <a href="{{ route('wisata.index') }}" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>Jelajahi Destinasi
            </a>
        </div>
    @else
        <div class="row">
            @foreach($bookings as $booking)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">#{{ $booking->kode_booking }}</h5>
                            <small class="text-muted">Dibuat: {{ $booking->created_at->format('d M Y H:i') }}</small>
                        </div>
                        <span class="badge 
                            @if($booking->status == 'pending') bg-warning
                            @elseif($booking->status == 'dikonfirmasi') bg-success
                            @elseif($booking->status == 'dibatalkan') bg-danger
                            @else bg-info @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                @if($booking->wisata->gambar)
                                    <img src="{{ asset('storage/wisata/' . $booking->wisata->gambar) }}" 
                                         class="img-fluid rounded" 
                                         alt="{{ $booking->wisata->nama_wisata }}">
                                @else
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                         style="height: 120px;">
                                        <i class="fas fa-mountain fa-2x text-white"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h6 class="fw-bold">{{ $booking->wisata->nama_wisata }}</h6>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-map-marker-alt fa-xs"></i> {{ $booking->wisata->lokasi }}
                                </p>
                                
                                <div class="row small mb-2">
                                    <div class="col-6">
                                        <strong>Tanggal Kunjungan:</strong><br>
                                        {{ \Carbon\Carbon::parse($booking->tanggal_kunjungan)->format('d M Y') }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Jumlah Tiket:</strong><br>
                                        {{ $booking->jumlah_tiket }} orang
                                    </div>
                                </div>
                                
                                <div class="row small mb-3">
                                    <div class="col-6">
                                        <strong>Harga Satuan:</strong><br>
                                        Rp {{ number_format($booking->wisata->harga_tiket, 0, ',', '.') }}
                                    </div>
                                    <div class="col-6">
                                        <strong>Total Harga:</strong><br>
                                        <span class="fw-bold text-primary">
                                            Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                
                                @if($booking->catatan)
                                    <p class="small mb-0">
                                        <strong>Catatan:</strong> {{ $booking->catatan }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            @if($booking->status == 'pending')
                                <form action="{{ route('wisatawan.booking.cancel', $booking) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-times me-1"></i>Batalkan
                                    </button>
                                </form>
                            @else
                                <span></span>
                            @endif
                            
                            @if($booking->status == 'selesai')
                                <a href="{{ route('wisatawan.ulasan.create', $booking) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-star me-1"></i>Beri Ulasan
                                </a>
                            @else
                                <a href="{{ route('wisata.show', $booking->wisata) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Lihat Wisata
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection