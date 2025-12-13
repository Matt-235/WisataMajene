@extends('layouts.app')

@section('title', ' - Register')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card shadow-lg animate__animated animate__fadeIn">
                <div class="card-header bg-success text-white text-center py-4">
                    <h3 class="my-0"><i class="fas fa-user-plus me-2"></i>Daftar Akun Baru</h3>
                    <p class="mb-0">Bergabung dengan Wisata Majene</p>
                </div>
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <!-- Nama -->
                        <div class="mb-4">
                            <label for="nama" class="form-label fw-bold">Nama Lengkap *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-user text-success"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('nama') is-invalid @enderror" 
                                       id="nama" 
                                       name="nama" 
                                       value="{{ old('nama') }}" 
                                       required 
                                       autocomplete="name" 
                                       autofocus>
                            </div>
                            @error('nama')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">Email *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-envelope text-success"></i>
                                </span>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autocomplete="email">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">Password *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-success"></i>
                                </span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required 
                                       autocomplete="new-password">
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-success"></i>
                                </span>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password">
                            </div>
                        </div>

                        <!-- Role Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Daftar sebagai *</label>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card role-card border-2 h-100 
                                        {{ old('role') == 'wisatawan' ? 'border-success' : 'border-light' }}" 
                                        style="cursor: pointer;" 
                                        onclick="selectRole('wisatawan')">
                                        <div class="card-body text-center py-4">
                                            <i class="fas fa-user fa-3x text-success mb-3"></i>
                                            <h5 class="card-title">Wisatawan</h5>
                                            <p class="card-text small">Mencari dan booking destinasi wisata</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card role-card border-2 h-100 
                                        {{ old('role') == 'pengelola_wisata' ? 'border-success' : 'border-light' }}" 
                                        style="cursor: pointer;" 
                                        onclick="selectRole('pengelola_wisata')">
                                        <div class="card-body text-center py-4">
                                            <i class="fas fa-business-time fa-3x text-success mb-3"></i>
                                            <h5 class="card-title">Pengelola Wisata</h5>
                                            <p class="card-text small">Mengelola destinasi wisata Anda</p>
                                            <small class="text-muted">* Membutuhkan verifikasi admin</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="role" id="role" value="{{ old('role', 'wisatawan') }}">
                            @error('role')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Optional Fields -->
                        <div id="optionalFields">
                            <!-- Phone -->
                            <div class="mb-4">
                                <label for="no_telepon" class="form-label fw-bold">No. Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-phone text-success"></i>
                                    </span>
                                    <input type="tel" 
                                           class="form-control @error('no_telepon') is-invalid @enderror" 
                                           id="no_telepon" 
                                           name="no_telepon" 
                                           value="{{ old('no_telepon') }}">
                                </div>
                                @error('no_telepon')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="mb-4">
                                <label for="alamat" class="form-label fw-bold">Alamat</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                          id="alamat" 
                                          name="alamat" 
                                          rows="3">{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                            </button>
                        </div>

                        <!-- Login Link -->
                        <div class="text-center">
                            <p class="mb-0">Sudah punya akun?
                                <a href="{{ route('login') }}" class="text-decoration-none fw-bold">
                                    Login di sini
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .role-card {
        transition: all 0.3s;
    }
    .role-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .role-card.active {
        border-color: #198754 !important;
        background-color: rgba(25, 135, 84, 0.05);
    }
</style>
@endpush

@push('scripts')
<script>
    function selectRole(role) {
        // Update hidden input
        document.getElementById('role').value = role;
        
        // Update card styles
        document.querySelectorAll('.role-card').forEach(card => {
            card.classList.remove('active');
            card.classList.remove('border-success');
            card.classList.add('border-light');
        });
        
        // Find the selected card and update its style
        const selectedCard = [...document.querySelectorAll('.role-card')].find(card => {
            return card.getAttribute('onclick').includes(role);
        });
        
        if (selectedCard) {
            selectedCard.classList.add('active');
            selectedCard.classList.add('border-success');
            selectedCard.classList.remove('border-light');
        }
    }
    
    // Initialize role selection
    document.addEventListener('DOMContentLoaded', function() {
        const initialRole = document.getElementById('role').value;
        selectRole(initialRole);
    });
</script>
@endpush