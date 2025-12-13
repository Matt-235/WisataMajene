@extends('layouts.app')

@section('title', ' - Destinasi Wisata')

@section('content')
<div class="container-fluid py-4">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="hero-section text-center">
                <h1 class="display-4 fw-bold text-white">Destinasi Wisata Majene</h1>
                <p class="lead text-white">Temukan keindahan alam yang menakjubkan</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('wisata.index') }}" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" 
                                       class="form-control" 
                                       name="search" 
                                       placeholder="Cari nama atau lokasi wisata..."
                                       value="{{ request('search') }}"
                                       id="searchInput">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="kategori" id="kategoriSelect">
                                    <option value="semua">Semua Kategori</option>
                                    @foreach($kategoriList as $kategori)
                                        @if(!empty($kategori))
                                        <option value="{{ $kategori }}" 
                                                {{ request('kategori') == $kategori ? 'selected' : '' }}>
                                            {{ $kategori }}
                                        </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">Rp</span>
                                    <input type="number" 
                                           class="form-control" 
                                           name="harga_max" 
                                           placeholder="Maks harga"
                                           value="{{ request('harga_max') }}"
                                           id="hargaMaxInput"
                                           min="0">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Results -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold mb-3">
                @if(request('search') || (request('kategori') && request('kategori') != 'semua') || request('harga_max'))
                    Hasil Pencarian
                @else
                    Semua Destinasi Wisata
                @endif
                <span class="badge bg-primary ms-2">{{ $wisata->total() }}</span>
            </h4>
        </div>
    </div>

    @if($wisata->isEmpty())
        <div class="row">
            <div class="col-12">
                <div class="card shadow text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Destinasi tidak ditemukan</h5>
                    <p class="text-muted">Coba dengan kata kunci atau filter yang berbeda</p>
                    <a href="{{ route('wisata.index') }}" class="btn btn-primary">
                        <i class="fas fa-redo me-2"></i>Reset Pencarian
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($wisata as $item)
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4 animate-on-scroll">
                <div class="card h-100 shadow-sm">
                    @if($item->gambar)
                        <img src="{{ asset('storage/wisata/' . $item->gambar) }}" 
                             class="card-img-top" 
                             alt="{{ $item->nama_wisata }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-secondary d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <i class="fas fa-mountain fa-3x text-white"></i>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold mb-0">{{ $item->nama_wisata }}</h5>
                            <span class="badge bg-info">{{ $item->kategori }}</span>
                        </div>
                        
                        <p class="text-muted small mb-2">
                            <i class="fas fa-map-marker-alt text-danger"></i> {{ Str::limit($item->lokasi, 30) }}
                        </p>
                        
                        <p class="card-text text-muted small mb-3">
                            {{ Str::limit($item->deskripsi, 100) }}
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="fw-bold text-primary">Rp {{ number_format($item->harga_tiket, 0, ',', '.') }}</span>
                                <small class="text-muted d-block">per orang</small>
                            </div>
                            <div class="text-end">
                                @php
                                    $rating = $item->ulasan->avg('rating') ?? 0;
                                @endphp
                                <div class="small">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($rating))
                                            <i class="fas fa-star text-warning"></i>
                                        @elseif($i - 0.5 <= $rating)
                                            <i class="fas fa-star-half-alt text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                    <span class="ms-1">({{ $item->ulasan->count() }})</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('wisata.show', $item) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-info-circle me-1"></i>Detail
                            </a>
                            @auth
                                @if(Auth::user()->role == 'wisatawan')
                                    <a href="{{ route('wisatawan.booking.create', $item) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-calendar-plus me-1"></i>Booking
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-calendar-plus me-1"></i>Login untuk Booking
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination - Diperbaiki -->
        @if($wisata->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if($wisata->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-angle-left"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $wisata->previousPageUrl() }}" aria-label="Previous">
                                        <i class="fas fa-angle-left"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @php
                                $current = $wisata->currentPage();
                                $last = $wisata->lastPage();
                                $start = max($current - 1, 1);
                                $end = min($current + 1, $last);
                            @endphp

                            {{-- First Page Link --}}
                            @if($start > 1)
                                <li class="page-item {{ 1 == $current ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $wisata->url(1) }}">1</a>
                                </li>
                                @if($start > 2)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                            @endif

                            {{-- Array Of Links --}}
                            @for($i = $start; $i <= $end; $i++)
                                <li class="page-item {{ $i == $current ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $wisata->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- Last Page Link --}}
                            @if($end < $last)
                                @if($end < $last - 1)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                                <li class="page-item {{ $last == $current ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $wisata->url($last) }}">{{ $last }}</a>
                                </li>
                            @endif

                            {{-- Next Page Link --}}
                            @if($wisata->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $wisata->nextPageUrl() }}" aria-label="Next">
                                        <i class="fas fa-angle-right"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-angle-right"></i>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        @endif
    @endif
</div>
@endsection

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(rgba(44, 62, 80, 0.9), rgba(52, 152, 219, 0.8)), 
                    url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');
        background-size: cover;
        background-position: center;
        padding: 60px 20px;
        border-radius: 15px;
    }
    
    .card {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    /* Pagination Styling */
    .pagination {
        margin-bottom: 0;
    }
    
    .page-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        color: #3498db;
        background-color: white;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease;
    }
    
    .page-link:hover {
        color: #2c3e50;
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    .page-item.active .page-link {
        background-color: #3498db;
        border-color: #3498db;
        color: white;
    }
    
    .page-item.disabled .page-link {
        color: #adb5bd;
        background-color: white;
        border-color: #dee2e6;
    }
    
    .page-link i {
        font-size: 0.8rem;
    }
    
    /* Responsive pagination */
    @media (max-width: 768px) {
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .page-link {
            padding: 0.4rem 0.6rem;
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 576px) {
        .page-link {
            padding: 0.3rem 0.5rem;
            font-size: 0.8rem;
        }
        
        .pagination li {
            margin: 2px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit form saat select kategori berubah
        document.getElementById('kategoriSelect').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
        
        // Auto-submit form saat harga max diisi dan enter ditekan
        document.getElementById('hargaMaxInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('filterForm').submit();
            }
        });
        
        // Auto-submit form saat search diisi dan enter ditekan
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('filterForm').submit();
            }
        });
        
        // Reset form button (jika ada)
        const resetButton = document.getElementById('resetButton');
        if (resetButton) {
            resetButton.addEventListener('click', function() {
                document.getElementById('searchInput').value = '';
                document.getElementById('kategoriSelect').value = 'semua';
                document.getElementById('hargaMaxInput').value = '';
                document.getElementById('filterForm').submit();
            });
        }
    });
</script>
@endpush