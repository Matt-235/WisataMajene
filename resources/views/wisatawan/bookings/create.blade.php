@extends('layouts.app')

@section('title', ' - Booking Wisata')

@section('styles')
<style>
    .wisata-card {
        border-left: 5px solid #3498db;
        transition: transform 0.3s;
    }
    .wisata-card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Informasi Wisata -->
            <div class="card wisata-card shadow mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($wisata->gambar)
                                <img src="{{ asset('storage/wisata/' . $wisata->gambar) }}" 
                                     class="img-fluid rounded" 
                                     alt="{{ $wisata->nama_wisata }}">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                     style="height: 150px;">
                                    <i class="fas fa-mountain fa-3x text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4 class="fw-bold">{{ $wisata->nama_wisata }}</h4>
                            <p class="text-muted">
                                <i class="fas fa-map-marker-alt text-danger"></i> {{ $wisata->lokasi }}
                            </p>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1">
                                        <strong>Kategori:</strong> {{ $wisata->kategori }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Kapasitas Harian:</strong> {{ $wisata->kapasitas_harian }} orang
                                    </p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1">
                                        <strong>Pengelola:</strong> {{ $wisata->pengelola->nama }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Status:</strong> 
                                        <span class="badge bg-success">Aktif</span>
                                    </p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h3 class="text-primary fw-bold">Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}</h3>
                                <small class="text-muted">Harga per orang</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Booking -->
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Form Booking</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('wisatawan.booking.store', $wisata) }}">
                        @csrf
                        
                        <!-- Tanggal Kunjungan -->
                        <div class="mb-4">
                            <label for="tanggal_kunjungan" class="form-label fw-bold">Tanggal Kunjungan *</label>
                            <input type="date" 
                                   class="form-control @error('tanggal_kunjungan') is-invalid @enderror" 
                                   id="tanggal_kunjungan" 
                                   name="tanggal_kunjungan" 
                                   value="{{ old('tanggal_kunjungan') }}" 
                                   min="{{ date('Y-m-d') }}" 
                                   required>
                            @error('tanggal_kunjungan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Pilih tanggal minimal besok hari</small>
                        </div>

                        <!-- Jumlah Tiket -->
                        <div class="mb-4">
                            <label for="jumlah_tiket" class="form-label fw-bold">Jumlah Tiket *</label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control @error('jumlah_tiket') is-invalid @enderror" 
                                       id="jumlah_tiket" 
                                       name="jumlah_tiket" 
                                       value="{{ old('jumlah_tiket', 1) }}" 
                                       min="1" 
                                       max="10" 
                                       required>
                                <span class="input-group-text">orang</span>
                            </div>
                            @error('jumlah_tiket')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 10 tiket per booking</small>
                        </div>

                        <!-- Catatan -->
                        <div class="mb-4">
                            <label for="catatan" class="form-label fw-bold">Catatan (Opsional)</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                      id="catatan" 
                                      name="catatan" 
                                      rows="3" 
                                      placeholder="Contoh: Membawa anak-anak, kebutuhan khusus, dll.">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ringkasan -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">Ringkasan Booking</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <p class="mb-2">Harga Tiket</p>
                                        <p class="mb-2">Jumlah Tiket</p>
                                        <p class="mb-0 fw-bold">Total</p>
                                    </div>
                                    <div class="col-6 text-end">
                                        <p class="mb-2" id="hargaDisplay">Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}</p>
                                        <p class="mb-2" id="jumlahDisplay">1</p>
                                        <p class="mb-0 fw-bold text-primary" id="totalDisplay">Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('wisata.show', $wisata) }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-calendar-check me-2"></i>Booking Sekarang
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hargaTiket = {{ $wisata->harga_tiket }};
        const jumlahInput = document.getElementById('jumlah_tiket');
        const jumlahDisplay = document.getElementById('jumlahDisplay');
        const totalDisplay = document.getElementById('totalDisplay');
        
        // Format angka ke format Rupiah
        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
        
        // Update ringkasan saat jumlah berubah
        function updateRingkasan() {
            const jumlah = parseInt(jumlahInput.value) || 0;
            const total = hargaTiket * jumlah;
            
            jumlahDisplay.textContent = jumlah;
            totalDisplay.textContent = formatRupiah(total);
        }
        
        // Inisialisasi
        updateRingkasan();
        
        // Event listener untuk perubahan jumlah
        jumlahInput.addEventListener('input', updateRingkasan);
        
        // Set tanggal minimal besok
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        const minDate = tomorrow.toISOString().split('T')[0];
        document.getElementById('tanggal_kunjungan').min = minDate;
    });
</script>
@endpush
@endsection