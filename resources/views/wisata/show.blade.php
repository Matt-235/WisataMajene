@extends('layouts.app')

@section('title', ' - ' . $wisata->nama_wisata)

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('wisata.index') }}">Destinasi</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $wisata->nama_wisata }}</li>
        </ol>
    </nav>

    <!-- Main Content -->
    <div class="row">
        <!-- Left Column - Images & Details -->
        <div class="col-lg-8 mb-4">
            <!-- Image Gallery -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-0">
                    @if($wisata->gambar)
                        <img src="{{ asset('storage/wisata/' . $wisata->gambar) }}" 
                             class="img-fluid rounded-top" 
                             alt="{{ $wisata->nama_wisata }}"
                             style="width: 100%; height: 400px; object-fit: cover;">
                    @else
                        <div class="bg-secondary d-flex align-items-center justify-content-center rounded-top" 
                             style="height: 400px;">
                            <i class="fas fa-mountain fa-5x text-white"></i>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-light">
                    <div class="row text-center">
                        <div class="col-md-3 mb-2 mb-md-0">
                            <i class="fas fa-map-marker-alt text-danger me-2"></i>
                            <strong>Lokasi</strong>
                            <p class="mb-0 small">{{ $wisata->lokasi }}</p>
                        </div>
                        <div class="col-md-3 mb-2 mb-md-0">
                            <i class="fas fa-tag text-primary me-2"></i>
                            <strong>Kategori</strong>
                            <p class="mb-0 small">{{ $wisata->kategori }}</p>
                        </div>
                        <div class="col-md-3 mb-2 mb-md-0">
                            <i class="fas fa-user text-success me-2"></i>
                            <strong>Pengelola</strong>
                            <p class="mb-0 small">{{ $wisata->pengelola->nama }}</p>
                        </div>
                        <div class="col-md-3">
                            <i class="fas fa-users text-warning me-2"></i>
                            <strong>Kapasitas</strong>
                            <p class="mb-0 small">{{ $wisata->kapasitas_harian }} orang/hari</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Deskripsi</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $wisata->deskripsi }}</p>
                    
                    @if($wisata->latitude && $wisata->longitude)
                        <div class="mt-4">
                            <h6 class="fw-bold mb-3"><i class="fas fa-map-marked-alt me-2"></i>Lokasi di Peta</h6>
                            <div class="bg-light rounded p-3 text-center">
                                <i class="fas fa-map fa-3x text-muted mb-2"></i>
                                <p class="mb-0 text-muted">Koordinat: {{ $wisata->latitude }}, {{ $wisata->longitude }}</p>
                                <small class="text-muted">Tampilan peta akan ditampilkan di sini</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reviews -->
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Ulasan Pengunjung</h5>
                </div>
                <div class="card-body">
                    @if($ulasan->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada ulasan untuk wisata ini</p>
                        </div>
                    @else
                        <!-- Average Rating -->
                        <div class="text-center mb-4">
                            @php
                                $avgRating = $wisata->ulasan->avg('rating') ?? 0;
                                $totalReviews = $wisata->ulasan->count();
                            @endphp
                            <h2 class="fw-bold text-warning">{{ number_format($avgRating, 1) }}</h2>
                            <div class="mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($avgRating))
                                        <i class="fas fa-star text-warning fa-lg"></i>
                                    @elseif($i - 0.5 <= $avgRating)
                                        <i class="fas fa-star-half-alt text-warning fa-lg"></i>
                                    @else
                                        <i class="far fa-star text-warning fa-lg"></i>
                                    @endif
                                @endfor
                            </div>
                            <p class="text-muted mb-0">Berdasarkan {{ $totalReviews }} ulasan</p>
                        </div>

                        <!-- Review List -->
                        <div class="review-list">
                            @foreach($ulasan as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <strong>{{ $review->wisatawan->nama }}</strong>
                                        <div class="small">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                                </div>
                                <p class="mb-0">{{ $review->komentar }}</p>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $ulasan->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Booking & Info -->
        <div class="col-lg-4 mb-4">
            <!-- Booking Card -->
            <div class="card shadow-lg sticky-top" style="top: 80px;">
                <div class="card-header bg-success text-white text-center py-3">
                    <h4 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Booking Wisata</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-primary">Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}</h3>
                        <p class="text-muted">Harga per orang</p>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3"><i class="fas fa-check-circle text-success me-2"></i>Fasilitas</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Akses ke lokasi wisata</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Bimbingan pengelola</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Area parkir kendaraan</li>
                            <li><i class="fas fa-check text-success me-2"></i> Toilet umum</li>
                        </ul>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3"><i class="fas fa-exclamation-circle text-warning me-2"></i>Syarat & Ketentuan</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i> Booking minimal 1 hari sebelum kunjungan</li>
                            <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i> Batas maksimal 10 tiket per booking</li>
                            <li><i class="fas fa-info-circle text-info me-2"></i> Pembatalan maksimal 1x24 jam sebelum kunjungan</li>
                        </ul>
                    </div>
                    
                    <!-- Booking Button -->
                    @auth
                        @if(Auth::user()->role == 'wisatawan')
                            <a href="{{ route('wisatawan.booking.create', $wisata) }}" class="btn btn-success btn-lg w-100 py-3">
                                <i class="fas fa-calendar-plus me-2"></i>Booking Sekarang
                            </a>
                        @elseif(Auth::user()->role == 'admin' || Auth::user()->role == 'pengelola_wisata')
                            <button class="btn btn-secondary btn-lg w-100 py-3" disabled>
                                <i class="fas fa-info-circle me-2"></i>Booking hanya untuk wisatawan
                            </button>
                        @endif
                    @else
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100 py-3 mb-2">
                                <i class="fas fa-sign-in-alt me-2"></i>Login untuk Booking
                            </a>
                            <small class="text-muted">Belum punya akun? 
                                <a href="{{ route('register') }}" class="text-decoration-none">Daftar di sini</a>
                            </small>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Recommendation -->
            @if($rekomendasi->isNotEmpty())
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Rekomendasi Lainnya</h6>
                    </div>
                    <div class="card-body">
                        @foreach($rekomendasi as $item)
                        <div class="d-flex mb-3">
                            @if($item->gambar)
                                <img src="{{ asset('storage/wisata/' . $item->gambar) }}" 
                                     class="rounded me-3" 
                                     style="width: 60px; height: 60px; object-fit: cover;" 
                                     alt="{{ $item->nama_wisata }}">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center me-3" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-mountain text-white"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="fw-bold mb-1">{{ $item->nama_wisata }}</h6>
                                <p class="text-muted small mb-1">
                                    <i class="fas fa-map-marker-alt fa-xs"></i> {{ Str::limit($item->lokasi, 20) }}
                                </p>
                                <p class="mb-0">
                                    <span class="fw-bold text-primary">Rp {{ number_format($item->harga_tiket, 0, ',', '.') }}</span>
                                </p>
                                <a href="{{ route('wisata.show', $item) }}" class="btn btn-sm btn-outline-info mt-1">
                                    <i class="fas fa-eye fa-xs"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection