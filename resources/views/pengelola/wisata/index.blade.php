@extends('layouts.app')

@section('title', ' - Kelola Wisata')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold">Kelola Wisata</h2>
                <a href="{{ route('pengelola.wisata.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Wisata
                </a>
            </div>
            <p class="text-muted">Kelola destinasi wisata yang Anda kelola</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    @if($wisata->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-mountain fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada wisata</h5>
                            <p class="text-muted">Mulai dengan menambahkan wisata pertama Anda</p>
                            <a href="{{ route('pengelola.wisata.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Wisata Pertama
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Wisata</th>
                                        <th>Lokasi</th>
                                        <th>Kategori</th>
                                        <th>Harga Tiket</th>
                                        <th>Kapasitas</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
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
                                                         class="rounded me-3" 
                                                         style="width: 50px; height: 50px; object-fit: cover;" 
                                                         alt="{{ $item->nama_wisata }}">
                                                @else
                                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center me-3" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-mountain text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $item->nama_wisata }}</strong>
                                                    <p class="text-muted mb-0 small">{{ Str::limit($item->deskripsi, 50) }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ Str::limit($item->lokasi, 30) }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $item->kategori }}</span>
                                        </td>
                                        <td>Rp {{ number_format($item->harga_tiket, 0, ',', '.') }}</td>
                                        <td>{{ $item->kapasitas_harian }} orang/hari</td>
                                        <td>
                                            @if($item->status == 'aktif')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('wisata.show', $item) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('pengelola.wisata.edit', $item) }}" 
                                                   class="btn btn-sm btn-warning" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger delete-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal"
                                                        data-wisata-id="{{ $item->id }}"
                                                        data-wisata-name="{{ $item->nama_wisata }}"
                                                        data-delete-url="{{ route('pengelola.wisata.destroy', $item) }}"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination - Diperbaiki -->
                        @if($wisata->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm">
                                    {{-- Previous Page Link --}}
                                    @if($wisata->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fas fa-chevron-left fa-xs"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $wisata->previousPageUrl() }}" aria-label="Previous">
                                                <i class="fas fa-chevron-left fa-xs"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @php
                                        $current = $wisata->currentPage();
                                        $last = $wisata->lastPage();
                                        $start = max($current - 2, 1);
                                        $end = min($current + 2, $last);
                                    @endphp

                                    {{-- First Page Link --}}
                                    @if($start > 1)
                                        <li class="page-item">
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
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $wisata->url($last) }}">{{ $last }}</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if($wisata->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $wisata->nextPageUrl() }}" aria-label="Next">
                                                <i class="fas fa-chevron-right fa-xs"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fas fa-chevron-right fa-xs"></i>
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

<!-- Single Delete Modal (outside table to prevent flicker) -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus wisata <strong id="wisataName"></strong>?</p>
                <div class="alert alert-danger mb-0">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span class="small">Data yang dihapus tidak dapat dikembalikan! Semua booking terkait wisata ini juga akan dihapus.</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Batal
                </button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-1"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Prevent flicker and improve modal transitions */
    .modal.fade .modal-dialog {
        transform: translate(0, -50px);
        transition: transform 0.3s ease-out;
    }
    
    .modal.show .modal-dialog {
        transform: translate(0, 0);
    }
    
    /* Smooth button hover effects */
    .btn-group .btn {
        transition: all 0.2s ease;
    }
    
    .btn-group .btn:hover {
        transform: translateY(-1px);
    }
    
    /* Table row hover effect */
    .table-hover tbody tr {
        transition: background-color 0.2s ease;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    /* Modal backdrop fix */
    .modal-backdrop {
        opacity: 0.5 !important;
    }
    
    /* Prevent content jumping */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Image styling */
    img[style*="width: 50px; height: 50px;"] {
        transition: transform 0.2s ease;
    }
    
    img[style*="width: 50px; height: 50px;"]:hover {
        transform: scale(1.05);
    }
    
    /* Pagination styling */
    .pagination {
        margin-bottom: 0;
    }
    
    .page-link {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border: 1px solid #dee2e6;
        color: #3498db;
        background-color: white;
        transition: all 0.2s ease;
    }
    
    .page-link:hover {
        color: #2c3e50;
        background-color: #f8f9fa;
        border-color: #dee2e6;
        transform: translateY(-1px);
    }
    
    .page-item.active .page-link {
        background-color: #3498db;
        border-color: #3498db;
        color: white;
    }
    
    .page-item.disabled .page-link {
        color: #6c757d;
        background-color: white;
        border-color: #dee2e6;
    }
    
    .page-link i {
        vertical-align: middle;
    }
    
    /* Pagination responsive */
    @media (max-width: 576px) {
        .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Get the modal and form elements
        const deleteModal = document.getElementById('deleteModal');
        const wisataName = document.getElementById('wisataName');
        const deleteForm = document.getElementById('deleteForm');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        
        // Variable to store current wisata data
        let currentWisata = null;
        
        // Handle delete button clicks
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Store wisata data
                currentWisata = {
                    id: this.getAttribute('data-wisata-id'),
                    name: this.getAttribute('data-wisata-name'),
                    url: this.getAttribute('data-delete-url')
                };
                
                // Update modal content
                wisataName.textContent = currentWisata.name;
                deleteForm.action = currentWisata.url;
                
                // Reset button state
                confirmDeleteBtn.innerHTML = '<i class="fas fa-trash me-1"></i>Hapus';
                confirmDeleteBtn.disabled = false;
            });
        });
        
        // Handle form submission
        deleteForm.addEventListener('submit', function(e) {
            if (!currentWisata) {
                e.preventDefault();
                return;
            }
            
            // Show loading state
            confirmDeleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menghapus...';
            confirmDeleteBtn.disabled = true;
            
            // Allow form to submit normally
        });
        
        // Reset modal when closed
        deleteModal.addEventListener('hidden.bs.modal', function() {
            currentWisata = null;
            wisataName.textContent = '';
            deleteForm.action = '#';
            
            // Reset button state
            confirmDeleteBtn.innerHTML = '<i class="fas fa-trash me-1"></i>Hapus';
            confirmDeleteBtn.disabled = false;
        });
        
        // Smooth modal animation
        const modalInstance = new bootstrap.Modal(deleteModal);
        
        // Handle modal show event
        deleteModal.addEventListener('show.bs.modal', function() {
            this.style.display = 'block';
        });
        
        // Handle modal shown event
        deleteModal.addEventListener('shown.bs.modal', function() {
            // Focus on cancel button for better UX
            this.querySelector('.btn-secondary').focus();
        });
        
        // Handle Escape key to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && deleteModal.classList.contains('show')) {
                modalInstance.hide();
            }
        });
        
        // Handle click outside modal to close
        deleteModal.addEventListener('click', function(e) {
            if (e.target === this) {
                modalInstance.hide();
            }
        });
    });
</script>
@endpush
@endsection