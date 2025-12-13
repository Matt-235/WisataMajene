@extends('layouts.app')

@section('title', ' - Kelola Wisata')

@section('content')
<div class="container-fluid py-3">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-2">Kelola Wisata</h2>
                    <p class="text-muted mb-3">Kelola semua wisata yang terdaftar dalam sistem</p>
                </div>
                <a href="{{ route('wisata.index') }}" class="btn btn-outline-primary btn-sm" target="_blank">
                    <i class="fas fa-external-link-alt me-1"></i>Lihat di Frontend
                </a>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card shadow-sm mb-3">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.wisata.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Status</label>
                    <select class="form-select form-select-sm" name="status">
                        <option value="semua">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label small text-muted mb-1">Cari Wisata</label>
                    <input type="text" 
                           name="search" 
                           class="form-control form-control-sm" 
                           placeholder="Cari nama wisata..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Total Wisata</h6>
                            <h4 class="fw-bold mb-0">{{ $wisata->total() }}</h4>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-mountain fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Wisata Aktif</h6>
                            <h4 class="fw-bold mb-0">{{ $wisata->where('status', 'aktif')->count() }}</h4>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Wisata Nonaktif</h6>
                            <h4 class="fw-bold mb-0">{{ $wisata->where('status', 'nonaktif')->count() }}</h4>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fas fa-pause-circle fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wisata List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-3">
                    @if($wisata->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-mountain fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada wisata ditemukan</h5>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-2">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Wisata</th>
                                        <th>Pengelola</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th style="width: 100px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($wisata as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ($wisata->perPage() * ($wisata->currentPage() - 1)) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->gambar)
                                                    <img src="{{ asset('storage/wisata/' . $item->gambar) }}" 
                                                         alt="{{ $item->nama_wisata }}"
                                                         class="rounded me-2"
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center me-2"
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-mountain text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong class="d-block" style="font-size: 0.9rem;">{{ $item->nama_wisata }}</strong>
                                                    <small class="text-muted">
                                                        <i class="fas fa-map-marker-alt fa-xs"></i> {{ Str::limit($item->lokasi, 30) }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="font-size: 0.9rem;">
                                            {{ $item->pengelola->nama ?? '-' }}
                                        </td>
                                        <td>
                                            <span class="badge bg-info" style="font-size: 0.75rem;">
                                                {{ $item->kategori }}
                                            </span>
                                        </td>
                                        <td style="font-size: 0.9rem;">
                                            Rp {{ number_format($item->harga_tiket, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if($item->status == 'aktif')
                                                <span class="badge bg-success" style="font-size: 0.75rem;">Aktif</span>
                                            @else
                                                <span class="badge bg-warning" style="font-size: 0.75rem;">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td style="font-size: 0.9rem;">
                                            {{ $item->created_at->format('d M Y') }}
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('wisata.show', $item) }}" 
                                                   class="btn btn-outline-info btn-sm px-2"
                                                   target="_blank"
                                                   title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <!-- TOMBOL DETAIL YANG DIKOREKSI -->
                                                <button type="button" 
                                                        class="btn btn-outline-warning btn-sm px-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#wisataDetailModal{{ $item->id }}"
                                                        title="Detail">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($wisata->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                            <div class="text-muted small">
                                Menampilkan {{ $wisata->firstItem() }} - {{ $wisata->lastItem() }} dari {{ $wisata->total() }} wisata
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    {{-- Previous Page Link --}}
                                    @if($wisata->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fas fa-chevron-left"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $wisata->previousPageUrl() }}" aria-label="Previous">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach($wisata->getUrlRange(1, $wisata->lastPage()) as $page => $url)
                                        @if($page == $wisata->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if($wisata->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $wisata->nextPageUrl() }}" aria-label="Next">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODALS DI LUAR TABLE -->
@foreach($wisata as $item)
<!-- Detail Modal untuk setiap wisata -->
<div class="modal fade" 
     id="wisataDetailModal{{ $item->id }}" 
     tabindex="-1" 
     aria-labelledby="wisataDetailModalLabel{{ $item->id }}" 
     aria-hidden="true"
     data-bs-backdrop="static"
     data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="wisataDetailModalLabel{{ $item->id }}">
                    <i class="fas fa-info-circle me-2"></i>Detail Wisata
                </h5>
                <button type="button" 
                        class="btn-close btn-close-white" 
                        data-bs-dismiss="modal" 
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Gambar Wisata -->
                    <div class="col-md-4 text-center mb-4">
                        @if($item->gambar)
                            <div class="mb-3">
                                <img src="{{ asset('storage/wisata/' . $item->gambar) }}" 
                                     alt="{{ $item->nama_wisata }}"
                                     class="img-fluid rounded shadow"
                                     style="max-height: 200px; object-fit: cover;">
                            </div>
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3 shadow"
                                 style="height: 150px;">
                                <i class="fas fa-mountain fa-3x text-muted"></i>
                            </div>
                        @endif
                        <h4 class="mb-2">{{ $item->nama_wisata }}</h4>
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            <span class="badge bg-info fs-6 px-3 py-2">
                                <i class="fas fa-tag me-1"></i>{{ $item->kategori }}
                            </span>
                            @if($item->status == 'aktif')
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    <i class="fas fa-check-circle me-1"></i>Aktif
                                </span>
                            @else
                                <span class="badge bg-warning fs-6 px-3 py-2">
                                    <i class="fas fa-pause-circle me-1"></i>Nonaktif
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Detail Informasi -->
                    <div class="col-md-8">
                        <div class="row">
                            <!-- Informasi Dasar -->
                            <div class="col-12 mb-3">
                                <h6 class="text-primary border-bottom pb-2">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                                </h6>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="card border-light shadow-sm h-100">
                                            <div class="card-body py-2">
                                                <small class="text-muted d-block">Pengelola</small>
                                                <strong>{{ $item->pengelola->nama ?? 'Tidak Diketahui' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-light shadow-sm h-100">
                                            <div class="card-body py-2">
                                                <small class="text-muted d-block">Harga Tiket</small>
                                                <strong class="text-success">Rp {{ number_format($item->harga_tiket, 0, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-light shadow-sm h-100">
                                            <div class="card-body py-2">
                                                <small class="text-muted d-block">Kapasitas Harian</small>
                                                <strong>{{ $item->kapasitas_harian }} orang</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-light shadow-sm h-100">
                                            <div class="card-body py-2">
                                                <small class="text-muted d-block">Koordinat</small>
                                                <small>
                                                    @if($item->latitude && $item->longitude)
                                                        {{ number_format($item->latitude, 6) }}, {{ number_format($item->longitude, 6) }}
                                                    @else
                                                        <span class="text-muted">Tidak tersedia</span>
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Lokasi -->
                            <div class="col-12 mb-3">
                                <h6 class="text-primary border-bottom pb-2">
                                    <i class="fas fa-map-marker-alt me-2"></i>Lokasi
                                </h6>
                                <div class="card border-light shadow-sm">
                                    <div class="card-body">
                                        <p class="mb-0">{{ $item->lokasi }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Deskripsi -->
                            <div class="col-12 mb-3">
                                <h6 class="text-primary border-bottom pb-2">
                                    <i class="fas fa-file-alt me-2"></i>Deskripsi
                                </h6>
                                <div class="card border-light shadow-sm">
                                    <div class="card-body" style="max-height: 150px; overflow-y: auto;">
                                        <p class="mb-0">{{ $item->deskripsi }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Statistik -->
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2">
                                    <i class="fas fa-chart-bar me-2"></i>Statistik
                                </h6>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="card border-light shadow-sm text-center">
                                            <div class="card-body py-2">
                                                <small class="text-muted d-block">Tanggal Dibuat</small>
                                                <strong>{{ $item->created_at->format('d M Y') }}</strong>
                                                <div class="text-muted small">{{ $item->created_at->format('H:i') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card border-light shadow-sm text-center">
                                            <div class="card-body py-2">
                                                <small class="text-muted d-block">Terakhir Update</small>
                                                <strong>{{ $item->updated_at->format('d M Y') }}</strong>
                                                <div class="text-muted small">{{ $item->updated_at->format('H:i') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card border-light shadow-sm text-center">
                                            <div class="card-body py-2">
                                                @php
                                                    $totalUlasan = $item->ulasan->count();
                                                    $avgRating = $item->ulasan->avg('rating') ?? 0;
                                                @endphp
                                                <small class="text-muted d-block">Rating & Ulasan</small>
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <span class="text-warning me-1">
                                                        <i class="fas fa-star"></i>
                                                    </span>
                                                    <strong>{{ number_format($avgRating, 1) }}</strong>
                                                    <span class="text-muted ms-2">
                                                        ({{ $totalUlasan }} ulasan)
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Tutup
                </button>
                <a href="{{ route('wisata.show', $item) }}" 
                   class="btn btn-primary"
                   target="_blank">
                    <i class="fas fa-external-link-alt me-1"></i>Lihat di Frontend
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- CSS FIX untuk mencegah flicker -->
<style>
    /* Fix untuk modal */
    .modal {
        z-index: 1060 !important;
    }
    
    .modal-backdrop {
        z-index: 1050 !important;
        opacity: 0.5 !important;
    }
    
    /* Nonaktifkan animasi jika menyebabkan masalah */
    .modal.fade .modal-dialog {
        transition: transform 0.15s ease-out;
    }
    
    /* Pastikan modal tidak berkedip saat show/hide */
    body.modal-open {
        overflow: hidden;
        padding-right: 0 !important;
    }
    
    /* Fix untuk modal yang tidak muncul */
    .modal.show {
        display: block !important;
    }
    
    /* Styling untuk card dalam modal */
    .modal .card {
        border: 1px solid #e9ecef;
    }
    
    /* Custom scrollbar */
    .modal-body [style*="max-height"]::-webkit-scrollbar {
        width: 6px;
    }
    
    .modal-body [style*="max-height"]::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .modal-body [style*="max-height"]::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 10px;
        }
        
        .modal-content {
            border-radius: 10px;
        }
        
        .modal-header {
            padding: 1rem;
        }
        
        .modal-body {
            padding: 1rem;
        }
    }
</style>

@push('scripts')
<script>
// SOLUSI SIMPLE TANPA FLICKER
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin Wisata Page Loaded');
    
    // 1. Inisialisasi tooltip sederhana
    setTimeout(function() {
        var tooltipList = [].slice.call(document.querySelectorAll('[title]'))
            .map(function (tooltipTriggerEl) {
                try {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                } catch (e) {
                    console.warn('Tooltip error:', e);
                    return null;
                }
            });
    }, 300);
    
    // 2. Fix khusus untuk modal wisata
    const modalElements = document.querySelectorAll('.modal[id^="wisataDetailModal"]');
    
    modalElements.forEach(function(modal) {
        // Initialize modal instance
        const modalInstance = new bootstrap.Modal(modal, {
            backdrop: true,
            keyboard: true,
            focus: true
        });
        
        // Simpan instance
        modal._modalInstance = modalInstance;
        
        // Custom event handling untuk mencegah flicker
        modal.addEventListener('show.bs.modal', function(event) {
            // Hentikan event bubbling
            if (event) {
                event.stopPropagation();
            }
            
            // Pastikan backdrop ada
            if (!document.querySelector('.modal-backdrop')) {
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                document.body.appendChild(backdrop);
            }
        });
        
        modal.addEventListener('hidden.bs.modal', function() {
            // Hapus backdrop
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            
            // Reset body style
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });
    });
    
    // 3. Event delegation untuk tombol detail
    document.addEventListener('click', function(e) {
        // Cari tombol detail yang diklik
        if (e.target.closest('.btn-outline-warning') && 
            e.target.closest('.btn-outline-warning').hasAttribute('data-bs-toggle') &&
            e.target.closest('.btn-outline-warning').getAttribute('data-bs-toggle') === 'modal') {
            
            const button = e.target.closest('.btn-outline-warning');
            const targetId = button.getAttribute('data-bs-target');
            
            if (targetId) {
                // Cegah default behavior
                e.preventDefault();
                e.stopPropagation();
                
                // Dapatkan modal element
                const modalElement = document.querySelector(targetId);
                
                if (modalElement && modalElement._modalInstance) {
                    // Tutup modal lain yang terbuka
                    document.querySelectorAll('.modal.show').forEach(openModal => {
                        if (openModal !== modalElement && openModal._modalInstance) {
                            openModal._modalInstance.hide();
                        }
                    });
                    
                    // Tampilkan modal yang diminta
                    setTimeout(() => {
                        modalElement._modalInstance.show();
                    }, 10);
                }
            }
        }
    });
    
    // 4. Solusi jika masih ada flicker: nonaktifkan animasi
    function disableModalAnimationsIfNeeded() {
        // Cek jika ada masalah dengan modal
        const checkModalIssues = localStorage.getItem('disableModalAnimations');
        
        if (checkModalIssues === 'true') {
            const style = document.createElement('style');
            style.textContent = `
                .modal.fade .modal-dialog {
                    transition: none !important;
                }
                .modal-backdrop.fade {
                    transition: none !important;
                }
                .fade {
                    transition: none !important;
                }
            `;
            document.head.appendChild(style);
            console.log('Modal animations disabled');
        }
    }
    
    // 5. Auto-hide alert setelah 5 detik
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            setTimeout(() => bsAlert.close(), 5000);
        });
    }, 5000);
    
    // Panggil fungsi jika perlu
    // disableModalAnimationsIfNeeded();
});

// Fungsi untuk debug
function debugModalIssues() {
    console.log('Modal elements found:', document.querySelectorAll('.modal').length);
    
    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(btn => {
        console.log('Button:', btn.getAttribute('data-bs-target'));
    });
}

// Uncomment untuk debug
// debugModalIssues();
</script>
@endpush
@endsection