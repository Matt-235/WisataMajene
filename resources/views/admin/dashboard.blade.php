@extends('layouts.app')

@section('title', ' - Dashboard Admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Dashboard Admin</h2>
            <p class="text-muted">Selamat datang, {{ Auth::user()->nama }}!</p>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 animate__animated animate__fadeInUp">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Total Pengguna</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $statistik['total_users'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 animate__animated animate__fadeInUp animate__delay-1s">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Total Wisata</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $statistik['total_wisata'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-mountain fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 animate__animated animate__fadeInUp animate__delay-2s">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Total Booking</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $statistik['total_bookings'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 animate__animated animate__fadeInUp animate__delay-3s">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-danger text-uppercase mb-1">
                                Verifikasi Pending</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $statistik['pending_verifikasi'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-clock fa-2x text-danger"></i>
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
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 fw-bold"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary w-100 h-100 py-3">
                                <i class="fas fa-users fa-2x mb-2"></i><br>
                                <span>Kelola Pengguna</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.wisata.index') }}" class="btn btn-outline-success w-100 h-100 py-3">
                                <i class="fas fa-mountain fa-2x mb-2"></i><br>
                                <span>Kelola Wisata</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-warning w-100 h-100 py-3">
                                <i class="fas fa-calendar-alt fa-2x mb-2"></i><br>
                                <span>Kelola Booking</span>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.users.index') }}?status=belum_terverifikasi&role=pengelola_wisata" 
                               class="btn btn-outline-danger w-100 h-100 py-3">
                                <i class="fas fa-user-check fa-2x mb-2"></i><br>
                                <span>Verifikasi Akun</span>
                                @if($statistik['pending_verifikasi'] > 0)
                                    <span class="badge bg-danger ms-2">{{ $statistik['pending_verifikasi'] }}</span>
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
        <div class="col-xl-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h6 class="m-0 fw-bold"><i class="fas fa-history me-2"></i>Booking Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Wisatawan</th>
                                    <th>Wisata</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings_terbaru as $booking)
                                <tr>
                                    <td>
                                        <small class="text-muted">{{ $booking->kode_booking }}</small>
                                    </td>
                                    <td>{{ $booking->wisatawan->nama }}</td>
                                    <td>{{ Str::limit($booking->wisata->nama_wisata, 20) }}</td>
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
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        Belum ada booking
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-warning">
                        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h6 class="m-0 fw-bold"><i class="fas fa-user-plus me-2"></i>Pengguna Baru</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengguna_baru as $user)
                                <tr>
                                    <td>{{ $user->nama }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role == 'admin')
                                            <span class="badge bg-danger">Admin</span>
                                        @elseif($user->role == 'pengelola_wisata')
                                            <span class="badge bg-warning">Pengelola</span>
                                        @else
                                            <span class="badge bg-success">Wisatawan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->status_verifikasi == 'terverifikasi')
                                            <span class="badge bg-success">Terverifikasi</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        Belum ada pengguna baru
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-success">
                        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection