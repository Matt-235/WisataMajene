@extends('layouts.app')

@section('title', ' - Kelola Booking')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Kelola Booking</h2>
            <p class="text-muted">Kelola booking untuk wisata Anda</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('pengelola.bookings.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <select class="form-select" name="status" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="dikonfirmasi" {{ request('status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" 
                               class="form-control" 
                               name="search" 
                               id="searchFilter"
                               placeholder="Cari kode booking atau nama wisatawan..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100" id="filterButton">
                            <i class="fas fa-search me-2"></i>Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    @if($bookings->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada booking</h5>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Wisatawan</th>
                                        <th>Wisata</th>
                                        <th>Tanggal Kunjungan</th>
                                        <th>Jumlah</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                    <tr>
                                        <td>
                                            <small class="text-muted">{{ $booking->kode_booking }}</small>
                                            <br>
                                            <small>{{ $booking->created_at->format('d M Y') }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $booking->wisatawan->nama }}</strong><br>
                                            <small class="text-muted">{{ $booking->wisatawan->email }}</small>
                                        </td>
                                        <td>{{ $booking->wisata->nama_wisata }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->tanggal_kunjungan)->format('d M Y') }}</td>
                                        <td>{{ $booking->jumlah_tiket }} orang</td>
                                        <td>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                                        <td>
                                            @if($booking->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($booking->status == 'dikonfirmasi')
                                                <span class="badge bg-success">Dikonfirmasi</span>
                                            @elseif($booking->status == 'dibatalkan')
                                                <span class="badge bg-danger">Dibatalkan</span>
                                            @else
                                                <span class="badge bg-info">Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" 
                                                        class="btn btn-sm btn-info detail-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#detailModal"
                                                        data-booking-id="{{ $booking->id }}"
                                                        data-booking-code="{{ $booking->kode_booking }}"
                                                        data-booking-date="{{ $booking->created_at->format('d M Y H:i') }}"
                                                        data-visit-date="{{ \Carbon\Carbon::parse($booking->tanggal_kunjungan)->format('d M Y') }}"
                                                        data-ticket-count="{{ $booking->jumlah_tiket }}"
                                                        data-total-price="{{ number_format($booking->total_harga, 0, ',', '.') }}"
                                                        data-booking-status="{{ $booking->status }}"
                                                        data-wisatawan-name="{{ $booking->wisatawan->nama }}"
                                                        data-wisatawan-email="{{ $booking->wisatawan->email }}"
                                                        data-wisatawan-phone="{{ $booking->wisatawan->no_telepon ?? '-' }}"
                                                        data-notes="{{ $booking->catatan ?? '-' }}"
                                                        data-wisata-name="{{ $booking->wisata->nama_wisata }}"
                                                        data-wisata-location="{{ $booking->wisata->lokasi }}"
                                                        data-wisata-description="{{ Str::limit($booking->wisata->deskripsi, 100) }}"
                                                        data-wisata-image="{{ $booking->wisata->gambar ? asset('storage/wisata/' . $booking->wisata->gambar) : '' }}"
                                                        data-update-url="{{ route('pengelola.booking.status.update', $booking) }}"
                                                        title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                
                                                @if($booking->status == 'pending')
                                                    <div class="btn-group" role="group">
                                                        <button type="button" 
                                                                class="btn btn-sm btn-success dropdown-toggle status-btn" 
                                                                data-bs-toggle="dropdown"
                                                                data-booking-id="{{ $booking->id }}"
                                                                title="Ubah Status">
                                                            <i class="fas fa-cog"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <form action="{{ route('pengelola.booking.status.update', $booking) }}" 
                                                                      method="POST" class="status-form">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="dikonfirmasi">
                                                                    <button type="submit" class="dropdown-item text-success">
                                                                        <i class="fas fa-check me-2"></i>Konfirmasi
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('pengelola.booking.status.update', $booking) }}" 
                                                                      method="POST" class="status-form">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="dibatalkan">
                                                                    <button type="submit" class="dropdown-item text-danger">
                                                                        <i class="fas fa-times me-2"></i>Batalkan
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @elseif($booking->status == 'dikonfirmasi')
                                                    <form action="{{ route('pengelola.booking.status.update', $booking) }}" 
                                                          method="POST"
                                                          class="d-inline status-form">
                                                        @csrf
                                                        <input type="hidden" name="status" value="selesai">
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-info"
                                                                title="Tandai Selesai">
                                                            <i class="fas fa-check-double"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $bookings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Universal Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">
                    <i class="fas fa-info-circle me-2"></i>Detail Booking
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Booking</h6>
                        <dl class="row">
                            <dt class="col-sm-5">Kode Booking</dt>
                            <dd class="col-sm-7" id="detailBookingCode"></dd>
                            
                            <dt class="col-sm-5">Tanggal Booking</dt>
                            <dd class="col-sm-7" id="detailBookingDate"></dd>
                            
                            <dt class="col-sm-5">Tanggal Kunjungan</dt>
                            <dd class="col-sm-7" id="detailVisitDate"></dd>
                            
                            <dt class="col-sm-5">Jumlah Tiket</dt>
                            <dd class="col-sm-7" id="detailTicketCount"></dd>
                            
                            <dt class="col-sm-5">Total Harga</dt>
                            <dd class="col-sm-7" id="detailTotalPrice"></dd>
                            
                            <dt class="col-sm-5">Status</dt>
                            <dd class="col-sm-7" id="detailStatus"></dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <h6>Informasi Wisatawan</h6>
                        <dl class="row">
                            <dt class="col-sm-5">Nama</dt>
                            <dd class="col-sm-7" id="detailWisatawanName"></dd>
                            
                            <dt class="col-sm-5">Email</dt>
                            <dd class="col-sm-7" id="detailWisatawanEmail"></dd>
                            
                            <dt class="col-sm-5">No. Telepon</dt>
                            <dd class="col-sm-7" id="detailWisatawanPhone"></dd>
                            
                            <dt class="col-sm-5">Catatan</dt>
                            <dd class="col-sm-7" id="detailNotes"></dd>
                        </dl>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Informasi Wisata</h6>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div id="detailWisataImage">
                                            <!-- Gambar akan dimuat via JavaScript -->
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h5 id="detailWisataName"></h5>
                                        <p class="text-muted mb-2">
                                            <i class="fas fa-map-marker-alt"></i> <span id="detailWisataLocation"></span>
                                        </p>
                                        <p class="mb-0" id="detailWisataDescription"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="detailModalFooter">
                <!-- Tombol aksi akan dimuat via JavaScript -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
    
    /* Status badge animation */
    .badge {
        transition: all 0.3s ease;
    }
    
    /* Loading state for forms */
    .loading {
        opacity: 0.7;
        pointer-events: none;
    }
    
    /* Dropdown menu styling */
    .dropdown-menu {
        border: 1px solid rgba(0,0,0,0.1);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    /* Wisata image container */
    #detailWisataImage img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
    }
    
    /* Filter form styling */
    #filterForm .form-control,
    #filterForm .form-select {
        transition: border-color 0.3s ease;
    }
    
    #filterForm .form-control:focus,
    #filterForm .form-select:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
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
        
        // Get modal elements
        const detailModal = document.getElementById('detailModal');
        const modalInstance = new bootstrap.Modal(detailModal);
        
        // Store current booking data
        let currentBooking = null;
        
        // Handle detail button clicks
        document.querySelectorAll('.detail-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Store booking data from data attributes
                currentBooking = {
                    id: this.getAttribute('data-booking-id'),
                    code: this.getAttribute('data-booking-code'),
                    bookingDate: this.getAttribute('data-booking-date'),
                    visitDate: this.getAttribute('data-visit-date'),
                    ticketCount: this.getAttribute('data-ticket-count'),
                    totalPrice: this.getAttribute('data-total-price'),
                    status: this.getAttribute('data-booking-status'),
                    wisatawanName: this.getAttribute('data-wisatawan-name'),
                    wisatawanEmail: this.getAttribute('data-wisatawan-email'),
                    wisatawanPhone: this.getAttribute('data-wisatawan-phone'),
                    notes: this.getAttribute('data-notes'),
                    wisataName: this.getAttribute('data-wisata-name'),
                    wisataLocation: this.getAttribute('data-wisata-location'),
                    wisataDescription: this.getAttribute('data-wisata-description'),
                    wisataImage: this.getAttribute('data-wisata-image'),
                    updateUrl: this.getAttribute('data-update-url')
                };
                
                // Update modal content
                updateModalContent();
                
                // Update modal footer actions
                updateModalFooter();
            });
        });
        
        // Function to update modal content
        function updateModalContent() {
            // Update booking information
            document.getElementById('detailBookingCode').textContent = currentBooking.code;
            document.getElementById('detailBookingDate').textContent = currentBooking.bookingDate;
            document.getElementById('detailVisitDate').textContent = currentBooking.visitDate;
            document.getElementById('detailTicketCount').textContent = currentBooking.ticketCount + ' orang';
            document.getElementById('detailTotalPrice').textContent = 'Rp ' + currentBooking.totalPrice;
            
            // Update status badge
            const statusElement = document.getElementById('detailStatus');
            let statusBadge = '';
            switch(currentBooking.status) {
                case 'pending':
                    statusBadge = '<span class="badge bg-warning">Pending</span>';
                    break;
                case 'dikonfirmasi':
                    statusBadge = '<span class="badge bg-success">Dikonfirmasi</span>';
                    break;
                case 'dibatalkan':
                    statusBadge = '<span class="badge bg-danger">Dibatalkan</span>';
                    break;
                case 'selesai':
                    statusBadge = '<span class="badge bg-info">Selesai</span>';
                    break;
            }
            statusElement.innerHTML = statusBadge;
            
            // Update wisatawan information
            document.getElementById('detailWisatawanName').textContent = currentBooking.wisatawanName;
            document.getElementById('detailWisatawanEmail').textContent = currentBooking.wisatawanEmail;
            document.getElementById('detailWisatawanPhone').textContent = currentBooking.wisatawanPhone;
            document.getElementById('detailNotes').textContent = currentBooking.notes;
            
            // Update wisata information
            document.getElementById('detailWisataName').textContent = currentBooking.wisataName;
            document.getElementById('detailWisataLocation').textContent = currentBooking.wisataLocation;
            document.getElementById('detailWisataDescription').textContent = currentBooking.wisataDescription;
            
            // Update wisata image
            const imageContainer = document.getElementById('detailWisataImage');
            if (currentBooking.wisataImage) {
                imageContainer.innerHTML = `<img src="${currentBooking.wisataImage}" alt="${currentBooking.wisataName}" class="img-fluid rounded">`;
            } else {
                imageContainer.innerHTML = `
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                        <i class="fas fa-mountain fa-3x text-white"></i>
                    </div>
                `;
            }
        }
        
        // Function to update modal footer actions
        function updateModalFooter() {
            const footer = document.getElementById('detailModalFooter');
            let buttons = '';
            
            if (currentBooking.status === 'pending') {
                buttons = `
                    <form action="${currentBooking.updateUrl}" method="POST" class="d-inline status-form">
                        @csrf
                        <input type="hidden" name="status" value="dibatalkan">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times me-1"></i>Batalkan
                        </button>
                    </form>
                    <form action="${currentBooking.updateUrl}" method="POST" class="d-inline status-form">
                        @csrf
                        <input type="hidden" name="status" value="dikonfirmasi">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-1"></i>Konfirmasi
                        </button>
                    </form>
                `;
            } else if (currentBooking.status === 'dikonfirmasi') {
                buttons = `
                    <form action="${currentBooking.updateUrl}" method="POST" class="d-inline status-form">
                        @csrf
                        <input type="hidden" name="status" value="selesai">
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-check-double me-1"></i>Tandai Selesai
                        </button>
                    </form>
                `;
            }
            
            footer.innerHTML = buttons + '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>';
            
            // Re-attach form submission handlers
            attachFormHandlers();
        }
        
        // Handle status form submissions
        function attachFormHandlers() {
            document.querySelectorAll('.status-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const status = this.querySelector('input[name="status"]').value;
                    let actionText = '';
                    
                    switch(status) {
                        case 'dikonfirmasi':
                            actionText = 'mengkonfirmasi';
                            break;
                        case 'dibatalkan':
                            actionText = 'membatalkan';
                            break;
                        case 'selesai':
                            actionText = 'menandai selesai';
                            break;
                    }
                    
                    if (confirm(`Apakah Anda yakin ingin ${actionText} booking ini?`)) {
                        // Show loading state
                        const submitBtn = this.querySelector('button[type="submit"]');
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memproses...';
                        submitBtn.disabled = true;
                        
                        // Submit form
                        this.submit();
                    }
                });
            });
        }
        
        // Attach handlers to existing status forms in table
        attachFormHandlers();
        
        // Reset modal when closed
        detailModal.addEventListener('hidden.bs.modal', function() {
            currentBooking = null;
        });
        
        // Handle filter form submission with loading state
        const filterForm = document.getElementById('filterForm');
        const filterButton = document.getElementById('filterButton');
        
        filterForm.addEventListener('submit', function() {
            const originalText = filterButton.innerHTML;
            filterButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mencari...';
            filterButton.disabled = true;
            
            // Re-enable button after 2 seconds in case of error
            setTimeout(() => {
                filterButton.innerHTML = originalText;
                filterButton.disabled = false;
            }, 2000);
        });
        
        // Handle Escape key to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && detailModal.classList.contains('show')) {
                modalInstance.hide();
            }
        });
        
        // Handle click outside modal to close
        detailModal.addEventListener('click', function(e) {
            if (e.target === this) {
                modalInstance.hide();
            }
        });
    });
</script>
@endpush
@endsection