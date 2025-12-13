@extends('layouts.app')

@section('title', ' - Profil')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user-circle me-2"></i>Profil Pengguna</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="avatar mb-3">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" 
                                     style="width: 150px; height: 150px; font-size: 60px;">
                                    {{ substr(Auth::user()->nama, 0, 1) }}
                                </div>
                            </div>
                            <h5 class="fw-bold">{{ Auth::user()->nama }}</h5>
                            <div class="mb-2">
                                @if(Auth::user()->role == 'admin')
                                    <span class="badge bg-danger">Admin</span>
                                @elseif(Auth::user()->role == 'pengelola_wisata')
                                    <span class="badge bg-warning">Pengelola Wisata</span>
                                @else
                                    <span class="badge bg-success">Wisatawan</span>
                                @endif
                            </div>
                            <div>
                                @if(Auth::user()->status_verifikasi == 'terverifikasi')
                                    <span class="badge bg-success">Terverifikasi</span>
                                @else
                                    <span class="badge bg-warning">Belum Diverifikasi</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Pribadi</h6>
                                </div>
                                <div class="card-body">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4">Nama Lengkap</dt>
                                        <dd class="col-sm-8">{{ Auth::user()->nama }}</dd>
                                        
                                        <dt class="col-sm-4">Email</dt>
                                        <dd class="col-sm-8">{{ Auth::user()->email }}</dd>
                                        
                                        <dt class="col-sm-4">No. Telepon</dt>
                                        <dd class="col-sm-8">{{ Auth::user()->no_telepon ?? '-' }}</dd>
                                        
                                        <dt class="col-sm-4">Alamat</dt>
                                        <dd class="col-sm-8">{{ Auth::user()->alamat ?? '-' }}</dd>
                                        
                                        <dt class="col-sm-4">Tanggal Daftar</dt>
                                        <dd class="col-sm-8">{{ Auth::user()->created_at->format('d M Y H:i') }}</dd>
                                    </dl>
                                </div>
                            </div>
                            
                            @if(Auth::user()->role == 'pengelola_wisata')
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-mountain me-2"></i>Statistik Pengelola</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-6 mb-3">
                                                <div class="bg-primary rounded p-3 text-white">
                                                    <i class="fas fa-mountain fa-2x mb-2"></i>
                                                    <h4 class="mb-0">{{ Auth::user()->wisataDikelola->count() }}</h4>
                                                    <small>Total Wisata</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="bg-success rounded p-3 text-white">
                                                    <i class="fas fa-calendar-check fa-2x mb-2"></i>
                                                    <h4 class="mb-0">{{ Auth::user()->wisataDikelola->flatMap->bookings->count() }}</h4>
                                                    <small>Total Booking</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if(Auth::user()->role == 'wisatawan')
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-history me-2"></i>Statistik Wisatawan</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-6 mb-3">
                                                <div class="bg-primary rounded p-3 text-white">
                                                    <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                                    <h4 class="mb-0">{{ Auth::user()->bookings->count() }}</h4>
                                                    <small>Total Booking</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="bg-success rounded p-3 text-white">
                                                    <i class="fas fa-star fa-2x mb-2"></i>
                                                    <h4 class="mb-0">{{ Auth::user()->ulasan->count() }}</h4>
                                                    <small>Total Ulasan</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('home') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    @if(Auth::user()->role == 'pengelola_wisata' && Auth::user()->status_verifikasi == 'belum_terverifikasi')
                        <button type="button" class="btn btn-warning" disabled>
                            <i class="fas fa-clock me-2"></i>Menunggu Verifikasi Admin
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection