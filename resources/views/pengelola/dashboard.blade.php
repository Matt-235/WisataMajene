@extends('layouts.app')

@section('title', ' - Dashboard Pengelola')

@section('content')
<div class="container-fluid py-4">
    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Dashboard Pengelola</h2>
            <p class="text-muted">Selamat datang, {{ Auth::user()->nama }}!</p>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="animation: fadeInUp 0.5s ease-out;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Total Wisata</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $statistik['total_wisata'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-mountain fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="animation: fadeInUp 0.5s ease-out 0.1s;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Total Booking</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $statistik['total_bookings'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" style="animation: fadeInUp 0.5s ease-out 0.2s;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Booking Pending</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $statistik['pending_bookings'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h6 class="m-0 fw-bold"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('pengelola.wisata.create') }}" class="btn btn-outline-success w-100 h-100 py-3">
                                <i class="fas fa-plus-circle fa-2x mb-2"></i><br>
                                <span>Tambah Wisata</span>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('pengelola.wisata.index') }}" class="btn btn-outline-primary w-100 h-100 py-3">
                                <i class="fas fa-list fa-2x mb-2"></i><br>
                                <span>Kelola Wisata</span>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('pengelola.bookings.index') }}" class="btn btn-outline-info w-100 h-100 py-3">
                                <i class="fas fa-calendar-alt fa-2x mb-2"></i><br>
                                <span>Kelola Booking</span>
                                @if($statistik['pending_bookings'] > 0)
                                    <span class="badge bg-danger ms-2">{{ $statistik['pending_bookings'] }}</span>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 fw-bold"><i class="fas fa-history me-2"></i>Booking Terbaru</h6>
                </div>
                <div class="card-body">
                    @if($bookings_terbaru->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada booking untuk wisata Anda</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Wisatawan</th>
                                        <th>Tanggal Kunjungan</th>
                                        <th>Jumlah Tiket</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings_terbaru as $booking)
                                    <tr>
                                        <td>
                                            <small class="text-muted">{{ $booking->kode_booking }}</small>
                                        </td>
                                        <td>{{ $booking->wisatawan->nama }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->tanggal_kunjungan)->format('d M Y') }}</td>
                                        <td>{{ $booking->jumlah_tiket }}</td>
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
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" 
                                                        class="btn btn-info" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#detailModal{{ $booking->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if($booking->status == 'pending')
                                                    <div class="dropdown">
                                                        <button class="btn btn-warning dropdown-toggle" 
                                                                type="button" 
                                                                data-bs-toggle="dropdown">
                                                            <i class="fas fa-cog"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <form action="{{ route('pengelola.booking.status.update', $booking) }}" 
                                                                      method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="dikonfirmasi">
                                                                    <button type="submit" class="dropdown-item text-success">
                                                                        <i class="fas fa-check me-2"></i>Konfirmasi
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('pengelola.booking.status.update', $booking) }}" 
                                                                      method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="dibatalkan">
                                                                    <button type="submit" class="dropdown-item text-danger">
                                                                        <i class="fas fa-times me-2"></i>Batalkan
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('pengelola.bookings.index') }}" class="btn btn-sm btn-info">
                        Lihat Semua Booking <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals for booking details (placed outside main content to prevent flicker) -->
@foreach($bookings_terbaru as $booking)
<div class="modal fade" id="detailModal{{ $booking->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $booking->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel{{ $booking->id }}">
                    <i class="fas fa-info-circle me-2"></i>Detail Booking
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Informasi Booking</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="40%"><strong>Kode Booking</strong></td>
                                        <td>{{ $booking->kode_booking }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Booking</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Kunjungan</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($booking->tanggal_kunjungan)->format('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jumlah Tiket</strong></td>
                                        <td>{{ $booking->jumlah_tiket }} orang</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Harga</strong></td>
                                        <td class="fw-bold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status</strong></td>
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
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Informasi Wisatawan</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="40%"><strong>Nama</strong></td>
                                        <td>{{ $booking->wisatawan->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email</strong></td>
                                        <td>{{ $booking->wisatawan->email }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. Telepon</strong></td>
                                        <td>{{ $booking->wisatawan->no_telepon ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        @if($booking->catatan)
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Catatan</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $booking->catatan }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                @if($booking->status == 'pending')
                <form action="{{ route('pengelola.booking.status.update', $booking) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="status" value="dibatalkan">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>Batalkan
                    </button>
                </form>
                <form action="{{ route('pengelola.booking.status.update', $booking) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="status" value="dikonfirmasi">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>Konfirmasi
                    </button>
                </form>
                @endif
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<style>
    /* Custom animations to prevent flicker */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Smooth modal transitions */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
    }
    
    /* Prevent content jump */
    .modal-body {
        overflow-y: auto;
        max-height: 70vh;
    }
    
    /* Card hover effects */
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }
    
    /* Table styling */
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.02);
    }
    
    /* Button group fix */
    .btn-group .dropdown-toggle::after {
        margin-left: 0.255em;
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
        
        // Smooth modal opening
        $('body').on('show.bs.modal', '.modal', function () {
            $(this).removeClass('fade');
            $(this).addClass('fade');
        });
        
        // Handle modal shown event
        $('body').on('shown.bs.modal', '.modal', function () {
            // Any additional code after modal is shown
        });
        
        // Handle modal hidden event
        $('body').on('hidden.bs.modal', '.modal', function () {
            // Any cleanup after modal is hidden
        });
        
        // Prevent default form submission for status updates
        $('form[action*="booking.status.update"]').on('submit', function(e) {
            if (!confirm('Apakah Anda yakin ingin mengubah status booking ini?')) {
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Memproses...');
            submitBtn.prop('disabled', true);
            
            // Restore button state after 2 seconds if submission fails
            setTimeout(() => {
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }, 2000);
        });
    });
</script>
@endpush
@endsection