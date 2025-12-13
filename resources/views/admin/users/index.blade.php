@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Kelola Pengguna</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-2">Kelola Pengguna</h2>
            <p class="text-muted mb-3">Kelola dan verifikasi pengguna sistem</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Role</label>
                        <select class="form-select form-select-sm" name="role" onchange="document.getElementById('filterForm').submit()">
                            <option value="semua">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="pengelola_wisata" {{ request('role') == 'pengelola_wisata' ? 'selected' : '' }}>Pengelola Wisata</option>
                            <option value="wisatawan" {{ request('role') == 'wisatawan' ? 'selected' : '' }}>Wisatawan</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Status Verifikasi</label>
                        <select class="form-select form-select-sm" name="status" onchange="document.getElementById('filterForm').submit()">
                            <option value="semua">Semua Status</option>
                            <option value="terverifikasi" {{ request('status') == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                            <option value="belum_terverifikasi" {{ request('status') == 'belum_terverifikasi' ? 'selected' : '' }}>Belum Diverifikasi</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="60">No</th>
                            <th>Pengguna</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Tanggal Daftar</th>
                            <th width="140" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $loop->iteration + ($users->perPage() * ($users->currentPage() - 1)) }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px; font-size: 1rem;">
                                            {{ strtoupper(substr($user->nama, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <strong class="d-block">{{ $user->nama }}</strong>
                                        <small class="text-muted">{{ $user->email }}</small>
                                        @if($user->no_telepon)
                                            <div class="mt-1">
                                                <small><i class="fas fa-phone fa-xs me-1"></i> {{ $user->no_telepon }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @switch($user->role)
                                    @case('admin')
                                        <span class="badge bg-danger px-3 py-2">Admin</span>
                                        @break
                                    @case('pengelola_wisata')
                                        <span class="badge bg-warning px-3 py-2">Pengelola</span>
                                        @break
                                    @default
                                        <span class="badge bg-success px-3 py-2">Wisatawan</span>
                                @endswitch
                            </td>
                            <td>
                                @if($user->role === 'pengelola_wisata')
                                    @if($user->status_verifikasi === 'terverifikasi')
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>Terverifikasi
                                        </span>
                                    @else
                                        <span class="badge bg-warning px-3 py-2">
                                            <i class="fas fa-clock me-1"></i>Menunggu
                                        </span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary px-3 py-2">Auto Verified</span>
                                @endif
                            </td>
                            <td>
                                <div class="small">
                                    <div>{{ $user->created_at->format('d M Y') }}</div>
                                    <div class="text-muted">{{ $user->created_at->format('H:i') }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- Detail Button -->
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailModal{{ $user->id }}"
                                            title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- Verify Button (only for unverified pengelola) -->
                                    @if($user->role === 'pengelola_wisata' && $user->status_verifikasi === 'belum_terverifikasi')
                                        <form action="{{ route('admin.user.verify', $user) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-success"
                                                    title="Verifikasi Akun"
                                                    onclick="return confirm('Verifikasi akun {{ $user->nama }}?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Delete Button (disable for self) -->
                                    @if($user->id !== auth()->id())
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $user->id }}"
                                                title="Hapus Pengguna">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <div class="text-muted small">
                    Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} pengguna
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        {{-- Previous Page Link --}}
                        @if($users->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $users->previousPageUrl() }}" aria-label="Previous">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach(range(1, $users->lastPage()) as $page)
                            @if($page == $users->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->url($page) }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if($users->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $users->nextPageUrl() }}" aria-label="Next">
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

            @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-users fa-3x text-muted"></i>
                </div>
                <h5 class="text-muted">Tidak ada pengguna ditemukan</h5>
                <p class="text-muted small">Coba ubah filter pencarian Anda</p>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-sync-alt me-1"></i>Reset Filter
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- MODALS -->
@foreach($users as $user)
<!-- Detail Modal -->
<div class="modal fade" id="detailModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4 mb-md-0">
                        <div class="avatar mb-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 100px; height: 100px; font-size: 2.5rem;">
                                {{ strtoupper(substr($user->nama, 0, 1)) }}
                            </div>
                        </div>
                        <h5>{{ $user->nama }}</h5>
                        <div class="mb-2">
                            @switch($user->role)
                                @case('admin')
                                    <span class="badge bg-danger px-3 py-2">Admin</span>
                                    @break
                                @case('pengelola_wisata')
                                    <span class="badge bg-warning px-3 py-2">Pengelola Wisata</span>
                                    @break
                                @default
                                    <span class="badge bg-success px-3 py-2">Wisatawan</span>
                            @endswitch
                        </div>
                        <div>
                            @if($user->role === 'pengelola_wisata')
                                @if($user->status_verifikasi === 'terverifikasi')
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i>Terverifikasi
                                    </span>
                                @else
                                    <span class="badge bg-warning px-3 py-2">
                                        <i class="fas fa-clock me-1"></i>Belum Terverifikasi
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="35%" class="text-muted">Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">No. Telepon</th>
                                <td>{{ $user->no_telepon ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Alamat</th>
                                <td>{{ $user->alamat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Tanggal Daftar</th>
                                <td>{{ $user->created_at->format('d F Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Terakhir Diperbarui</th>
                                <td>{{ $user->updated_at->format('d F Y H:i') }}</td>
                            </tr>
                            @if($user->role === 'pengelola_wisata')
                            <tr>
                                <th class="text-muted">Jumlah Wisata</th>
                                <td>
                                    {{ $user->wisataDikelola->count() }} wisata
                                    @if($user->wisataDikelola->count() > 0)
                                        <a href="javascript:void(0)" 
                                           class="badge bg-info text-decoration-none ms-2"
                                           data-bs-toggle="collapse" 
                                           data-bs-target="#wisataList{{ $user->id }}">
                                            Lihat
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="collapse" id="wisataList{{ $user->id }}">
                                        <div class="card card-body p-2 mt-2">
                                            <ul class="list-unstyled mb-0">
                                                @foreach($user->wisataDikelola->take(5) as $wisata)
                                                <li class="py-1">
                                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                    {{ $wisata->nama_wisata }}
                                                    <span class="badge bg-{{ $wisata->status === 'aktif' ? 'success' : 'secondary' }} ms-2">
                                                        {{ $wisata->status }}
                                                    </span>
                                                </li>
                                                @endforeach
                                                @if($user->wisataDikelola->count() > 5)
                                                <li class="py-1 text-muted small">
                                                    ... dan {{ $user->wisataDikelola->count() - 5 }} wisata lainnya
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endif
                            @if($user->role === 'wisatawan')
                            <tr>
                                <th class="text-muted">Jumlah Booking</th>
                                <td>
                                    {{ $user->bookings->count() }} booking
                                    @if($user->bookings->count() > 0)
                                        <span class="text-muted small ms-2">
                                            {{ $user->bookings->where('status', 'selesai')->count() }} selesai
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                @if($user->role === 'pengelola_wisata' && $user->status_verifikasi === 'belum_terverifikasi')
                    <form action="{{ route('admin.user.verify', $user) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-1"></i>Verifikasi Akun
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="fas fa-exclamation"></i>
                    </div>
                    <h5 class="mb-2">Hapus Pengguna?</h5>
                    <p class="text-muted">Anda akan menghapus pengguna berikut:</p>
                </div>

                <div class="card border mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar me-3">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px; font-size: 1.2rem;">
                                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                                </div>
                            </div>
                            <div>
                                <strong class="d-block">{{ $user->nama }}</strong>
                                <small class="text-muted">{{ $user->email }}</small>
                                <div class="mt-1">
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'pengelola_wisata' ? 'warning' : 'success') }}">
                                        {{ $user->role }}
                                    </span>
                                    @if($user->role === 'pengelola_wisata')
                                        <span class="badge bg-{{ $user->status_verifikasi === 'terverifikasi' ? 'success' : 'warning' }} ms-1">
                                            {{ $user->status_verifikasi }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Warning berdasarkan role -->
                @if($user->role === 'pengelola_wisata')
                <div class="alert alert-warning small mb-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Perhatian:</strong> Semua wisata yang dikelola oleh {{ $user->nama }} akan dinonaktifkan.
                </div>
                @endif

                @if($user->role === 'wisatawan')
                <div class="alert alert-info small mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Semua booking dan ulasan dari {{ $user->nama }} akan dihapus.
                </div>
                @endif

                @if($user->role === 'admin')
                <div class="alert alert-danger small mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Peringatan Tinggi:</strong> Menghapus akun admin dapat mempengaruhi sistem.
                </div>
                @endif

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="confirmDelete{{ $user->id }}" required>
                    <label class="form-check-label small" for="confirmDelete{{ $user->id }}">
                        Saya mengerti konsekuensi dari penghapusan ini
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" id="deleteForm{{ $user->id }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="btn btn-danger"
                            id="deleteButton{{ $user->id }}"
                            disabled>
                        <i class="fas fa-trash me-1"></i>Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal CSS Fix -->
<style>
    /* FIX untuk flicker modal */
    .modal {
        overflow: hidden !important;
    }
    
    .modal-dialog {
        transform: translate(0, 0) !important;
    }
    
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
    }
    
    .modal.show .modal-dialog {
        transform: none;
    }
    
    .modal-backdrop {
        opacity: 0.5 !important;
    }
    
    /* Nonaktifkan animasi jika masih bermasalah */
    body.modal-open {
        overflow: hidden !important;
        padding-right: 0 !important;
    }
    
    /* Fix untuk z-index */
    .modal {
        z-index: 1060 !important;
    }
    
    .modal-backdrop {
        z-index: 1050 !important;
    }
    
    /* Custom styling untuk tabel */
    .table th {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        color: #6c757d;
    }
    
    .table td {
        font-size: 0.9rem;
    }
    
    /* Animasi smooth untuk avatar */
    .avatar > div {
        transition: all 0.2s ease;
    }
    
    .avatar > div:hover {
        transform: scale(1.05);
    }
    
    /* Styling untuk badge */
    .badge {
        font-weight: 500;
    }
    
    /* Custom pagination */
    .pagination {
        margin-bottom: 0;
    }
    
    .page-link {
        border-color: #dee2e6;
    }
    
    .page-item.active .page-link {
        background-color: #3498db;
        border-color: #3498db;
    }
</style>

@push('scripts')
<script>
// Fix untuk flicker modal
document.addEventListener('DOMContentLoaded', function() {
    // 1. Inisialisasi tooltip dengan cara yang benar
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // 2. Fix untuk modal - prevent multiple initialization
    const modalElements = document.querySelectorAll('.modal');
    modalElements.forEach(function(modal) {
        // Prevent multiple event listeners
        modal.removeEventListener('show.bs.modal', handleModalShow);
        modal.removeEventListener('hide.bs.modal', handleModalHide);
        
        modal.addEventListener('show.bs.modal', handleModalShow);
        modal.addEventListener('hide.bs.modal', handleModalHide);
        
        // Initialize modal instance
        const modalInstance = new bootstrap.Modal(modal);
        modal._modalInstance = modalInstance;
    });
    
    // 3. Enable/disable delete button based on checkbox
    @foreach($users as $user)
    document.getElementById('confirmDelete{{ $user->id }}')?.addEventListener('change', function() {
        const deleteButton = document.getElementById('deleteButton{{ $user->id }}');
        if (deleteButton) {
            deleteButton.disabled = !this.checked;
        }
    });
    @endforeach
    
    // 4. Prevent event bubbling
    document.addEventListener('click', function(e) {
        if (e.target.closest('[data-bs-toggle="modal"]')) {
            const target = e.target.closest('[data-bs-toggle="modal"]');
            const modalId = target.getAttribute('data-bs-target');
            if (modalId) {
                const modal = document.querySelector(modalId);
                if (modal && modal._modalInstance) {
                    e.preventDefault();
                    e.stopPropagation();
                    modal._modalInstance.show();
                }
            }
        }
    }, true);
    
    // 5. Reset modal state saat ditutup
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function() {
            // Reset checkbox konfirmasi
            const checkboxes = this.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = false);
            
            // Reset delete button state
            const deleteButtons = this.querySelectorAll('button[type="submit"]');
            deleteButtons.forEach(btn => {
                if (btn.closest('form')?.id?.startsWith('deleteForm')) {
                    btn.disabled = true;
                }
            });
        });
    });
});

// Modal event handlers
function handleModalShow(e) {
    e.stopPropagation();
    
    // Tambahkan class untuk mencegah body scroll
    document.body.classList.add('modal-open');
    document.body.style.paddingRight = '0px';
    
    // Tambahkan backdrop
    if (!document.querySelector('.modal-backdrop')) {
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        document.body.appendChild(backdrop);
    }
}

function handleModalHide(e) {
    e.stopPropagation();
    
    // Hapus class modal-open
    document.body.classList.remove('modal-open');
    document.body.style.paddingRight = '';
    
    // Hapus backdrop
    const backdrop = document.querySelector('.modal-backdrop');
    if (backdrop) {
        backdrop.remove();
    }
}

// Solusi extreme: Nonaktifkan semua animasi Bootstrap jika masih flicker
function disableModalAnimations() {
    const style = document.createElement('style');
    style.id = 'modal-fix-style';
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
        .modal {
            animation: none !important;
        }
    `;
    document.head.appendChild(style);
}

// Uncomment baris berikut jika masih ada flicker
// disableModalAnimations();
</script>
@endpush
@endsection