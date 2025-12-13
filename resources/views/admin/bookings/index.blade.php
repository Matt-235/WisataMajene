@extends('layouts.app')

@section('title', ' - Kelola Booking')

@section('content')
<div class="container-fluid py-3">
    <!-- Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-2">Kelola Booking</h2>
                    <p class="text-muted mb-3">Kelola semua pemesanan wisata dalam sistem</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow-sm mb-3">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.bookings.index') }}" id="filterFormBookings" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Status Booking</label>
                    <select class="form-select form-select-sm" name="status" onchange="this.form.submit()">
                        <option value="semua">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="dikonfirmasi" {{ request('status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Cari Booking/Wisatawan</label>
                    <input type="text" 
                           name="search" 
                           class="form-control form-control-sm" 
                           placeholder="Cari kode booking atau nama wisatawan..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Tanggal Kunjungan</label>
                    <input type="date" 
                           name="tanggal" 
                           class="form-control form-control-sm" 
                           value="{{ request('tanggal') }}">
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Total Booking</h6>
                            <h4 class="fw-bold mb-0">{{ $bookings->total() }}</h4>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-2">
                            <i class="fas fa-calendar-alt fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Pending</h6>
                            <h4 class="fw-bold mb-0">{{ $bookings->where('status', 'pending')->count() }}</h4>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-2">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Dikonfirmasi</h6>
                            <h4 class="fw-bold mb-0">{{ $bookings->where('status', 'dikonfirmasi')->count() }}</h4>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-2">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Selesai</h6>
                            <h4 class="fw-bold mb-0">{{ $bookings->where('status', 'selesai')->count() }}</h4>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-2">
                            <i class="fas fa-flag-checkered fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-3">
                    @if($bookings->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada booking ditemukan</h5>
                            <p class="text-muted small">Coba ubah filter pencarian Anda</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-2">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Kode Booking</th>
                                        <th>Wisatawan</th>
                                        <th>Wisata</th>
                                        <th>Tanggal Kunjungan</th>
                                        <th>Jumlah Tiket</th>
                                        <th>Total Harga</th>
                                        <th>Status</th>
                                        <th style="width: 130px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                    <tr>
                                        <td>{{ $loop->iteration + ($bookings->perPage() * ($bookings->currentPage() - 1)) }}</td>
                                        <td>
                                            <span class="badge bg-dark font-monospace">
                                                {{ $booking->kode_booking }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-2">
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                                         style="width: 32px; height: 32px; font-size: 0.85rem;">
                                                        {{ strtoupper(substr($booking->wisatawan->nama, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <strong class="d-block" style="font-size: 0.9rem;">{{ $booking->wisatawan->nama }}</strong>
                                                    <small class="text-muted">{{ $booking->wisatawan->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="font-size: 0.9rem;">
                                                <strong>{{ $booking->wisata->nama_wisata }}</strong>
                                                <div class="text-muted small">
                                                    <i class="fas fa-map-marker-alt fa-xs me-1"></i>
                                                    {{ Str::limit($booking->wisata->lokasi, 25) }}
                                                </div>
                                            </div>
                                        </td>
                                        <td style="font-size: 0.9rem;">
                                            <div class="text-center">
                                                <div>{{ $booking->tanggal_kunjungan->format('d M Y') }}</div>
                                                <div class="text-muted small">
                                                    {{ $booking->created_at->format('H:i') }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center" style="font-size: 0.9rem;">
                                            {{ $booking->jumlah_tiket }}
                                        </td>
                                        <td style="font-size: 0.9rem;">
                                            <strong class="text-success">
                                                Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                                            </strong>
                                        </td>
                                        <td>
                                            @switch($booking->status)
                                                @case('pending')
                                                    <span class="badge bg-warning px-3 py-2">
                                                        <i class="fas fa-clock me-1"></i>Pending
                                                    </span>
                                                    @break
                                                @case('dikonfirmasi')
                                                    <span class="badge bg-success px-3 py-2">
                                                        <i class="fas fa-check-circle me-1"></i>Dikonfirmasi
                                                    </span>
                                                    @break
                                                @case('selesai')
                                                    <span class="badge bg-info px-3 py-2">
                                                        <i class="fas fa-flag-checkered me-1"></i>Selesai
                                                    </span>
                                                    @break
                                                @case('dibatalkan')
                                                    <span class="badge bg-danger px-3 py-2">
                                                        <i class="fas fa-times-circle me-1"></i>Dibatalkan
                                                    </span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- Detail Button -->
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-primary btn-booking-detail"
                                                        data-booking-id="{{ $booking->id }}"
                                                        title="Detail Booking">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                
                                                <!-- Status Update Button -->
                                                @if(in_array($booking->status, ['pending', 'dikonfirmasi']))
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-warning btn-update-status"
                                                            data-booking-id="{{ $booking->id }}"
                                                            title="Update Status">
                                                        <i class="fas fa-exchange-alt"></i>
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
                        @if($bookings->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                            <div class="text-muted small">
                                Menampilkan {{ $bookings->firstItem() }} - {{ $bookings->lastItem() }} dari {{ $bookings->total() }} booking
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    {{-- Previous Page Link --}}
                                    @if($bookings->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fas fa-chevron-left"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $bookings->previousPageUrl() }}" aria-label="Previous">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach($bookings->getUrlRange(1, $bookings->lastPage()) as $page => $url)
                                        @if($page == $bookings->currentPage())
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
                                    @if($bookings->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $bookings->nextPageUrl() }}" aria-label="Next">
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

<!-- MODALS SECTION -->
@foreach($bookings as $booking)
<!-- Detail Modal -->
<div class="modal fade" 
     id="bookingDetailModal{{ $booking->id }}" 
     tabindex="-1" 
     aria-labelledby="bookingDetailModalLabel{{ $booking->id }}" 
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="bookingDetailModalLabel{{ $booking->id }}">
                    <i class="fas fa-info-circle me-2"></i>Detail Booking
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Header Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-light shadow-sm">
                            <div class="card-body">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-barcode me-2"></i>Informasi Booking
                                </h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="40%">Kode Booking</th>
                                        <td>
                                            <span class="badge bg-dark font-monospace">
                                                {{ $booking->kode_booking }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @switch($booking->status)
                                                @case('pending')
                                                    <span class="badge bg-warning px-3 py-2">
                                                        <i class="fas fa-clock me-1"></i>Pending
                                                    </span>
                                                    @break
                                                @case('dikonfirmasi')
                                                    <span class="badge bg-success px-3 py-2">
                                                        <i class="fas fa-check-circle me-1"></i>Dikonfirmasi
                                                    </span>
                                                    @break
                                                @case('selesai')
                                                    <span class="badge bg-info px-3 py-2">
                                                        <i class="fas fa-flag-checkered me-1"></i>Selesai
                                                    </span>
                                                    @break
                                                @case('dibatalkan')
                                                    <span class="badge bg-danger px-3 py-2">
                                                        <i class="fas fa-times-circle me-1"></i>Dibatalkan
                                                    </span>
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Booking</th>
                                        <td>{{ $booking->created_at->format('d F Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Kunjungan</th>
                                        <td>
                                            <strong>{{ $booking->tanggal_kunjungan->format('d F Y') }}</strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-light shadow-sm">
                            <div class="card-body">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-money-bill-wave me-2"></i>Informasi Pembayaran
                                </h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="40%">Jumlah Tiket</th>
                                        <td>
                                            <span class="badge bg-secondary px-3 py-2">
                                                {{ $booking->jumlah_tiket }} tiket
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Harga per Tiket</th>
                                        <td>Rp {{ number_format($booking->wisata->harga_tiket, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Harga</th>
                                        <td>
                                            <h5 class="text-success fw-bold mb-0">
                                                Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                                            </h5>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-light shadow-sm">
                            <div class="card-body">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user me-2"></i>Informasi Wisatawan
                                </h6>
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <div class="avatar mb-3">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" 
                                                 style="width: 80px; height: 80px; font-size: 2rem;">
                                                {{ strtoupper(substr($booking->wisatawan->nama, 0, 1)) }}
                                            </div>
                                        </div>
                                        <h6>{{ $booking->wisatawan->nama }}</h6>
                                        <span class="badge bg-success">Wisatawan</span>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <th width="40%">Email</th>
                                                        <td>{{ $booking->wisatawan->email }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>No. Telepon</th>
                                                        <td>{{ $booking->wisatawan->no_telepon ?? '-' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <th width="40%">Alamat</th>
                                                        <td>{{ $booking->wisatawan->alamat ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Terdaftar Sejak</th>
                                                        <td>{{ $booking->wisatawan->created_at->format('d M Y') }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wisata Info -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border-light shadow-sm">
                            <div class="card-body">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-map-marked-alt me-2"></i>Informasi Wisata
                                </h6>
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        @if($booking->wisata->gambar)
                                            <img src="{{ asset('storage/wisata/' . $booking->wisata->gambar) }}" 
                                                 alt="{{ $booking->wisata->nama_wisata }}"
                                                 class="img-fluid rounded mb-3"
                                                 style="max-height: 150px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3"
                                                 style="height: 150px;">
                                                <i class="fas fa-mountain fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        <h6>{{ $booking->wisata->nama_wisata }}</h6>
                                        <div class="d-flex justify-content-center gap-2">
                                            <span class="badge bg-info">{{ $booking->wisata->kategori }}</span>
                                            <span class="badge bg-{{ $booking->wisata->status == 'aktif' ? 'success' : 'warning' }}">
                                                {{ $booking->wisata->status }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th width="30%">Lokasi</th>
                                                <td>{{ $booking->wisata->lokasi }}</td>
                                            </tr>
                                            <tr>
                                                <th>Pengelola</th>
                                                <td>{{ $booking->wisata->pengelola->nama ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Kapasitas Harian</th>
                                                <td>{{ $booking->wisata->kapasitas_harian }} orang</td>
                                            </tr>
                                            <tr>
                                                <th>Deskripsi</th>
                                                <td>
                                                    <div style="max-height: 100px; overflow-y: auto;">
                                                        {{ $booking->wisata->deskripsi }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Catatan jika ada -->
                @if($booking->catatan)
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card border-light shadow-sm">
                            <div class="card-body">
                                <h6 class="text-primary mb-2">
                                    <i class="fas fa-sticky-note me-2"></i>Catatan
                                </h6>
                                <p class="mb-0">{{ $booking->catatan }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Tutup
                </button>
                @if(in_array($booking->status, ['pending', 'dikonfirmasi']))
                <button type="button" 
                        class="btn btn-warning btn-update-status-modal"
                        data-booking-id="{{ $booking->id }}">
                    <i class="fas fa-exchange-alt me-1"></i>Update Status
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" 
     id="updateStatusModal{{ $booking->id }}" 
     tabindex="-1" 
     aria-labelledby="updateStatusModalLabel{{ $booking->id }}" 
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="updateStatusModalLabel{{ $booking->id }}">
                    <i class="fas fa-exchange-alt me-2"></i>Update Status Booking
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-warning text-white d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <h5>Update Status Booking</h5>
                    <p class="text-muted mb-0">Kode: <strong>{{ $booking->kode_booking }}</strong></p>
                </div>

                <div class="alert alert-info small mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Informasi:</strong> 
                    Wisatawan: {{ $booking->wisatawan->nama }} | 
                    Wisata: {{ $booking->wisata->nama_wisata }}
                </div>

                <form action="{{ route('admin.booking.status.update', $booking) }}" method="POST" id="statusForm{{ $booking->id }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Status Saat Ini</label>
                        <div class="form-control bg-light">
                            @switch($booking->status)
                                @case('pending')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i>Pending
                                    </span>
                                    @break
                                @case('dikonfirmasi')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Dikonfirmasi
                                    </span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ $booking->status }}</span>
                            @endswitch
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="statusSelect{{ $booking->id }}" class="form-label">
                            <strong>Pilih Status Baru</strong>
                        </label>
                        <select class="form-select" id="statusSelect{{ $booking->id }}" name="status" required>
                            <option value="">-- Pilih Status --</option>
                            @if($booking->status == 'pending')
                                <option value="dikonfirmasi">Dikonfirmasi</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            @elseif($booking->status == 'dikonfirmasi')
                                <option value="selesai">Selesai</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            @endif
                        </select>
                        <div class="form-text">
                            @if($booking->status == 'pending')
                                <span class="text-warning">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    Status "Dikonfirmasi" akan mengkonfirmasi booking.
                                </span>
                            @elseif($booking->status == 'dikonfirmasi')
                                <span class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Status "Selesai" akan menandai kunjungan telah dilakukan.
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="catatanStatus{{ $booking->id }}" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" 
                                  id="catatanStatus{{ $booking->id }}" 
                                  name="catatan" 
                                  rows="2" 
                                  placeholder="Tambahkan catatan jika perlu..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="statusForm{{ $booking->id }}" class="btn btn-warning">
                    <i class="fas fa-save me-1"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- CSS Fix untuk Modal -->
<style>
    /* Fix untuk modal */
    .modal {
        z-index: 1060 !important;
    }
    
    .modal-backdrop {
        z-index: 1050 !important;
    }
    
    /* Nonaktifkan animasi jika menyebabkan flicker */
    body.modal-open {
        overflow: hidden;
        padding-right: 0 !important;
    }
    
    /* Styling untuk card dalam modal */
    .modal .card {
        border: 1px solid rgba(0,0,0,.125);
        border-radius: 0.5rem;
    }
    
    .modal .card-body {
        padding: 1.25rem;
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
    
    /* Badge styling */
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    /* Avatar styling */
    .avatar > div {
        transition: transform 0.2s ease;
    }
    
    .avatar > div:hover {
        transform: scale(1.05);
    }
    
    /* Table styling */
    .table-sm th, .table-sm td {
        padding: 0.3rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 0.5rem;
        }
        
        .modal-content {
            border-radius: 0.5rem;
        }
        
        .btn-group-sm > .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    }
</style>

@push('scripts')
<script>
// SOLUSI TANPA FLICKER UNTUK MODAL BOOKING
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin Bookings Page Loaded');
    
    // 1. Inisialisasi tooltip
    setTimeout(function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            try {
                new bootstrap.Tooltip(tooltipTriggerEl, {
                    animation: false
                });
            } catch (e) {
                console.warn('Tooltip error:', e);
            }
        });
    }, 300);
    
    // 2. Inisialisasi modal booking detail
    const modalElements = document.querySelectorAll('.modal[id^="bookingDetailModal"], .modal[id^="updateStatusModal"]');
    const modalInstances = [];
    
    modalElements.forEach(function(modal) {
        try {
            // Initialize modal
            const modalInstance = new bootstrap.Modal(modal, {
                backdrop: 'static',
                keyboard: true,
                focus: true
            });
            
            // Save instance
            modal._modalInstance = modalInstance;
            modalInstances.push(modalInstance);
            
            // Event untuk handle modal show
            modal.addEventListener('show.bs.modal', function(event) {
                if (event) {
                    event.stopPropagation();
                }
                
                // Tutup modal lain yang terbuka
                modalInstances.forEach(instance => {
                    if (instance._element !== modal) {
                        try {
                            instance.hide();
                        } catch (e) {
                            console.warn('Error hiding modal:', e);
                        }
                    }
                });
                
                // Tambahkan backdrop
                setTimeout(() => {
                    if (!document.querySelector('.modal-backdrop')) {
                        const backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        document.body.appendChild(backdrop);
                    }
                    
                    // Update body classes
                    document.body.classList.add('modal-open');
                    document.body.style.overflow = 'hidden';
                }, 10);
            });
            
            // Event untuk handle modal hide
            modal.addEventListener('hidden.bs.modal', function() {
                // Hapus backdrop
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
                
                // Reset body
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            });
            
        } catch (error) {
            console.error('Error initializing modal:', modal.id, error);
        }
    });
    
    // 3. Event delegation untuk tombol detail
    document.addEventListener('click', function(e) {
        // Tombol Detail Booking
        if (e.target.closest('.btn-booking-detail')) {
            e.preventDefault();
            e.stopPropagation();
            
            const button = e.target.closest('.btn-booking-detail');
            const bookingId = button.getAttribute('data-booking-id');
            const modalId = `#bookingDetailModal${bookingId}`;
            const modalElement = document.querySelector(modalId);
            
            if (modalElement && modalElement._modalInstance) {
                modalElement._modalInstance.show();
            }
        }
        
        // Tombol Update Status (di luar modal)
        if (e.target.closest('.btn-update-status')) {
            e.preventDefault();
            e.stopPropagation();
            
            const button = e.target.closest('.btn-update-status');
            const bookingId = button.getAttribute('data-booking-id');
            const modalId = `#updateStatusModal${bookingId}`;
            const modalElement = document.querySelector(modalId);
            
            if (modalElement && modalElement._modalInstance) {
                modalElement._modalInstance.show();
            }
        }
        
        // Tombol Update Status (di dalam modal detail)
        if (e.target.closest('.btn-update-status-modal')) {
            e.preventDefault();
            e.stopPropagation();
            
            const button = e.target.closest('.btn-update-status-modal');
            const bookingId = button.getAttribute('data-booking-id');
            
            // Tutup modal detail
            const detailModal = document.querySelector(`#bookingDetailModal${bookingId}`);
            if (detailModal && detailModal._modalInstance) {
                detailModal._modalInstance.hide();
            }
            
            // Buka modal update status setelah delay
            setTimeout(() => {
                const statusModal = document.querySelector(`#updateStatusModal${bookingId}`);
                if (statusModal && statusModal._modalInstance) {
                    statusModal._modalInstance.show();
                }
            }, 300);
        }
    });
    
    // 4. Auto-submit filter saat select berubah
    const statusSelect = document.querySelector('select[name="status"]');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            setTimeout(() => {
                document.getElementById('filterFormBookings').submit();
            }, 100);
        });
    }
    
    // 5. Validasi form update status
    document.querySelectorAll('form[id^="statusForm"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const select = this.querySelector('select[name="status"]');
            if (!select.value) {
                e.preventDefault();
                select.focus();
                select.classList.add('is-invalid');
                
                // Tambahkan pesan error
                let errorDiv = select.nextElementSibling;
                if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = 'Silakan pilih status baru';
                    select.parentNode.appendChild(errorDiv);
                }
                
                return false;
            }
            
            // Konfirmasi sebelum submit
            if (!confirm('Anda yakin ingin mengubah status booking ini?')) {
                e.preventDefault();
                return false;
            }
        });
    });
    
    // 6. Cleanup saat halaman di-unload
    window.addEventListener('beforeunload', function() {
        modalInstances.forEach(instance => {
            try {
                instance.hide();
            } catch (e) {
                // Ignore errors
            }
        });
    });
});

// Fungsi untuk disable animasi modal jika masih ada flicker
function disableModalAnimationsForBookings() {
    const style = document.createElement('style');
    style.id = 'booking-modal-animation-fix';
    style.textContent = `
        .modal.fade .modal-dialog {
            transition: none !important;
        }
        .modal-backdrop.fade {
            transition: none !important;
        }
        .modal {
            animation: none !important;
        }
    `;
    document.head.appendChild(style);
    console.log('Modal animations disabled for bookings');
}

// Uncomment baris berikut jika masih ada flicker
// setTimeout(disableModalAnimationsForBookings, 500);
</script>
@endpush
@endsection