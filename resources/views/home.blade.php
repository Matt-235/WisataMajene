@extends('layouts.app')

@section('title', ' - Beranda')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center animate__animated animate__fadeIn">
            <h1 class="display-4 fw-bold mb-4">Selamat Datang di Wisata Majene</h1>
            <p class="lead mb-4">Temukan keindahan alam yang memukau dan pengalaman tak terlupakan</p>
            <a href="{{ route('wisata.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-search"></i> Jelajahi Destinasi
            </a>
        </div>
    </section>

    <!-- Statistik -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h2 class="fw-bold text-primary">{{ $statistik['total_wisata'] }}</h2>
                            <p class="text-muted mb-0">Destinasi Wisata</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h2 class="fw-bold text-success">{{ $statistik['total_kategori'] }}</h2>
                            <p class="text-muted mb-0">Kategori Wisata</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h2 class="fw-bold text-warning">24/7</h2>
                            <p class="text-muted mb-0">Dukungan Online</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h2 class="fw-bold text-info">100%</h2>
                            <p class="text-muted mb-0">Kepuasan Pengunjung</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="fw-bold">Mengapa Memilih Wisata Majene?</h2>
                    <p class="text-muted">Pengalaman terbaik menanti Anda</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4 animate-on-scroll">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="icon-box mb-3">
                                <i class="fas fa-map-marked-alt fa-3x text-primary"></i>
                            </div>
                            <h4 class="card-title">Destinasi Eksotis</h4>
                            <p class="card-text">Temukan berbagai destinasi wisata alam yang masih alami dan terjaga keasliannya.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4 animate-on-scroll">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="icon-box mb-3">
                                <i class="fas fa-calendar-check fa-3x text-success"></i>
                            </div>
                            <h4 class="card-title">Booking Mudah</h4>
                            <p class="card-text">Sistem booking online yang sederhana dan terintegrasi untuk kenyamanan Anda.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4 animate-on-scroll">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="icon-box mb-3">
                                <i class="fas fa-headset fa-3x text-info"></i>
                            </div>
                            <h4 class="card-title">Dukungan 24/7</h4>
                            <p class="card-text">Tim support kami siap membantu Anda kapan saja dalam perjalanan wisata.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Destinasi Populer -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="fw-bold">Destinasi Populer</h2>
                    <p class="text-muted">Rekomendasi terbaik untuk Anda</p>
                </div>
            </div>
            <div class="row">
                @if($wisataPopuler->isEmpty())
                    <div class="col-12 text-center py-4">
                        <i class="fas fa-mountain fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada destinasi wisata</p>
                    </div>
                @else
                    @foreach($wisataPopuler as $wisata)
                    <div class="col-md-4 mb-4 animate-on-scroll">
                        <div class="card h-100 shadow-sm">
                            @if($wisata->gambar)
                                <img src="{{ asset('storage/wisata/' . $wisata->gambar) }}" 
                                     class="card-img-top" 
                                     alt="{{ $wisata->nama_wisata }}"
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="fas fa-mountain fa-3x text-white"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title mb-0">{{ $wisata->nama_wisata }}</h5>
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-star"></i> 
                                        {{ number_format($wisata->ulasan_avg_rating ?? 0, 1) }}
                                    </span>
                                </div>
                                <p class="card-text text-muted">
                                    <i class="fas fa-map-marker-alt text-danger fa-xs"></i> 
                                    {{ Str::limit($wisata->lokasi, 40) }}
                                </p>
                                <p class="card-text text-muted small">{{ Str::limit($wisata->deskripsi, 80) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary">
                                        Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}
                                    </span>
                                    <a href="{{ route('wisata.show', $wisata) }}" class="btn btn-outline-primary btn-sm">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('wisata.index') }}" class="btn btn-primary">
                    Lihat Semua Destinasi <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Destinasi Pantai -->
    @if($wisataPantai->isNotEmpty())
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="fw-bold">Destinasi Pantai</h2>
                    <p class="text-muted">Nikmati keindahan pantai Majene</p>
                </div>
            </div>
            <div class="row">
                @foreach($wisataPantai as $wisata)
                <div class="col-md-4 mb-4 animate-on-scroll">
                    <div class="card h-100 shadow-sm">
                        @if($wisata->gambar)
                            <img src="{{ asset('storage/wisata/' . $wisata->gambar) }}" 
                                 class="card-img-top" 
                                 alt="{{ $wisata->nama_wisata }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="fas fa-umbrella-beach fa-3x text-white"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $wisata->nama_wisata }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit($wisata->deskripsi, 100) }}</p>
                            <a href="{{ route('wisata.show', $wisata) }}" class="btn btn-primary btn-sm">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Destinasi Alam -->
    @if($wisataAlam->isNotEmpty())
    <section class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="fw-bold">Destinasi Alam</h2>
                    <p class="text-muted">Eksplorasi keindahan alam Majene</p>
                </div>
            </div>
            <div class="row">
                @foreach($wisataAlam as $wisata)
                <div class="col-md-4 mb-4 animate-on-scroll">
                    <div class="card h-100 shadow-sm">
                        @if($wisata->gambar)
                            <img src="{{ asset('storage/wisata/' . $wisata->gambar) }}" 
                                 class="card-img-top" 
                                 alt="{{ $wisata->nama_wisata }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="fas fa-tree fa-3x text-white"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $wisata->nama_wisata }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit($wisata->deskripsi, 100) }}</p>
                            <a href="{{ route('wisata.show', $wisata) }}" class="btn btn-primary btn-sm">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">Siap Memulai Petualangan Anda?</h2>
            <p class="lead mb-4">Daftar sekarang dan nikmati kemudahan booking destinasi wisata favorit Anda</p>
            @auth
                <a href="{{ route('wisata.index') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-search me-2"></i> Jelajahi Wisata
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-light btn-lg me-3">
                    <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
                </a>
                <a href="{{ route('wisata.index') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-search me-2"></i> Jelajahi Wisata
                </a>
            @endauth
        </div>
    </section>
@endsection