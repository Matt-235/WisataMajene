@extends('layouts.app')

@section('title', ' - Edit Wisata')

@section('styles')
<style>
    .image-preview {
        width: 200px;
        height: 150px;
        border: 2px dashed #ddd;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        cursor: pointer;
        transition: border-color 0.3s;
    }
    .image-preview:hover {
        border-color: #3498db;
    }
    .image-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }
    .image-preview-placeholder {
        text-align: center;
        color: #999;
    }
    .image-preview-placeholder i {
        font-size: 48px;
        margin-bottom: 10px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Wisata: {{ $wisata->nama_wisata }}</h4>
                </div>
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('pengelola.wisata.update', $wisata) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Nama Wisata -->
                                <div class="mb-4">
                                    <label for="nama_wisata" class="form-label fw-bold">Nama Wisata *</label>
                                    <input type="text" 
                                           class="form-control @error('nama_wisata') is-invalid @enderror" 
                                           id="nama_wisata" 
                                           name="nama_wisata" 
                                           value="{{ old('nama_wisata', $wisata->nama_wisata) }}" 
                                           required>
                                    @error('nama_wisata')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Deskripsi -->
                                <div class="mb-4">
                                    <label for="deskripsi" class="form-label fw-bold">Deskripsi *</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                              id="deskripsi" 
                                              name="deskripsi" 
                                              rows="5" 
                                              required>{{ old('deskripsi', $wisata->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Lokasi dan Koordinat -->
                                <div class="row mb-4">
                                    <div class="col-md-8">
                                        <label for="lokasi" class="form-label fw-bold">Lokasi *</label>
                                        <input type="text" 
                                               class="form-control @error('lokasi') is-invalid @enderror" 
                                               id="lokasi" 
                                               name="lokasi" 
                                               value="{{ old('lokasi', $wisata->lokasi) }}" 
                                               required>
                                        @error('lokasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="kategori" class="form-label fw-bold">Kategori *</label>
                                        <select class="form-select @error('kategori') is-invalid @enderror" 
                                                id="kategori" 
                                                name="kategori" 
                                                required>
                                            <option value="">Pilih Kategori</option>
                                            <option value="Pantai" {{ old('kategori', $wisata->kategori) == 'Pantai' ? 'selected' : '' }}>Pantai</option>
                                            <option value="Gunung" {{ old('kategori', $wisata->kategori) == 'Gunung' ? 'selected' : '' }}>Gunung</option>
                                            <option value="Air Terjun" {{ old('kategori', $wisata->kategori) == 'Air Terjun' ? 'selected' : '' }}>Air Terjun</option>
                                            <option value="Taman" {{ old('kategori', $wisata->kategori) == 'Taman' ? 'selected' : '' }}>Taman</option>
                                            <option value="Museum" {{ old('kategori', $wisata->kategori) == 'Museum' ? 'selected' : '' }}>Museum</option>
                                            <option value="Kuliner" {{ old('kategori', $wisata->kategori) == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                                            <option value="Budaya" {{ old('kategori', $wisata->kategori) == 'Budaya' ? 'selected' : '' }}>Budaya</option>
                                        </select>
                                        @error('kategori')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Latitude dan Longitude -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="latitude" class="form-label fw-bold">Latitude</label>
                                        <input type="number" 
                                               step="any" 
                                               class="form-control @error('latitude') is-invalid @enderror" 
                                               id="latitude" 
                                               name="latitude" 
                                               value="{{ old('latitude', $wisata->latitude) }}">
                                        @error('latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Contoh: -3.5432</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="longitude" class="form-label fw-bold">Longitude</label>
                                        <input type="number" 
                                               step="any" 
                                               class="form-control @error('longitude') is-invalid @enderror" 
                                               id="longitude" 
                                               name="longitude" 
                                               value="{{ old('longitude', $wisata->longitude) }}">
                                        @error('longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Contoh: 119.1234</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Gambar -->
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Gambar Wisata</label>
                                    <div class="image-preview mb-3" id="imagePreview">
                                        @if($wisata->gambar)
                                            <img src="{{ asset('storage/wisata/' . $wisata->gambar) }}" alt="{{ $wisata->nama_wisata }}">
                                        @else
                                            <div class="image-preview-placeholder">
                                                <i class="fas fa-camera"></i>
                                                <p>Klik untuk upload</p>
                                            </div>
                                        @endif
                                    </div>
                                    <input type="file" 
                                           class="form-control @error('gambar') is-invalid @enderror" 
                                           id="gambar" 
                                           name="gambar" 
                                           accept="image/*" 
                                           style="display: none;">
                                    @error('gambar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Ukuran maksimal: 2MB. Format: JPG, PNG, GIF</small>
                                    @if($wisata->gambar)
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="hapus_gambar" name="hapus_gambar">
                                            <label class="form-check-label text-danger" for="hapus_gambar">
                                                Hapus gambar saat ini
                                            </label>
                                        </div>
                                    @endif
                                </div>

                                <!-- Harga Tiket dan Kapasitas -->
                                <div class="mb-4">
                                    <label for="harga_tiket" class="form-label fw-bold">Harga Tiket (Rp) *</label>
                                    <input type="number" 
                                           class="form-control @error('harga_tiket') is-invalid @enderror" 
                                           id="harga_tiket" 
                                           name="harga_tiket" 
                                           value="{{ old('harga_tiket', $wisata->harga_tiket) }}" 
                                           min="0" 
                                           required>
                                    @error('harga_tiket')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="kapasitas_harian" class="form-label fw-bold">Kapasitas Harian *</label>
                                    <input type="number" 
                                           class="form-control @error('kapasitas_harian') is-invalid @enderror" 
                                           id="kapasitas_harian" 
                                           name="kapasitas_harian" 
                                           value="{{ old('kapasitas_harian', $wisata->kapasitas_harian) }}" 
                                           min="1" 
                                           required>
                                    @error('kapasitas_harian')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Jumlah maksimal pengunjung per hari</small>
                                </div>

                                <!-- Status -->
                                <div class="mb-4">
                                    <label for="status" class="form-label fw-bold">Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" 
                                            name="status" 
                                            required>
                                        <option value="aktif" {{ old('status', $wisata->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="nonaktif" {{ old('status', $wisata->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('pengelola.wisata.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-2"></i>Update Wisata
                                    </button>
                                </div>
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
        const imageInput = document.getElementById('gambar');
        const imagePreview = document.getElementById('imagePreview');
        
        // Click pada preview untuk trigger input file
        imagePreview.addEventListener('click', function() {
            imageInput.click();
        });
        
        // Preview gambar saat dipilih
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Auto-format harga tiket
        const hargaInput = document.getElementById('harga_tiket');
        hargaInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    });
</script>
@endpush
@endsection