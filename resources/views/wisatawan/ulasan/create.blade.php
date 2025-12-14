@extends('layouts.app')

@section('title', 'Beri Ulasan - ' . $booking->wisata->nama_wisata)

@push('styles')
<style>
    .rating-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 30px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    
    .booking-info-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border-left: 4px solid #667eea;
    }
    
    .rating-stars {
        direction: rtl;
        display: inline-block;
        unicode-bidi: bidi-override;
    }
    
    .rating-stars input {
        display: none;
    }
    
    .rating-stars label {
        color: #ddd;
        font-size: 30px;
        padding: 0 5px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .rating-stars label:hover,
    .rating-stars label:hover ~ label,
    .rating-stars input:checked ~ label {
        color: #ffc107;
    }
    
    .rating-stars input:checked + label {
        color: #ffc107;
        animation: pulse 0.5s;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
    
    .rating-display {
        font-size: 18px;
        color: #ffc107;
        margin-top: 10px;
    }
    
    .wisata-image {
        height: 150px;
        width: 100%;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 15px;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-size: 0.95rem;
    }
    
    .info-item i {
        width: 24px;
        color: #667eea;
        margin-right: 10px;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .btn-cancel {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #6c757d;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .btn-cancel:hover {
        background: #e9ecef;
        color: #495057;
    }
    
    .character-count {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 5px;
    }
    
    .character-count.warning {
        color: #e74c3c;
    }
    
    .review-guidelines {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 30px;
        border-left: 4px solid #28a745;
    }
    
    .review-guidelines h6 {
        color: #28a745;
        margin-bottom: 15px;
    }
    
    .review-guidelines ul {
        margin: 0;
        padding-left: 20px;
    }
    
    .review-guidelines li {
        margin-bottom: 8px;
        font-size: 0.9rem;
        color: #495057;
    }
</style>
@endpush

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('wisatawan.bookings') }}">Booking Saya</a></li>
            <li class="breadcrumb-item active">Beri Ulasan</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header dengan Judul -->
            <div class="rating-container text-center animate__animated animate__fadeIn">
                <h1 class="mb-3"><i class="fas fa-star me-2"></i>Beri Ulasan</h1>
                <p class="lead mb-0">Bagikan pengalaman Anda mengunjungi {{ $booking->wisata->nama_wisata }}</p>
            </div>

            <!-- Informasi Booking -->
            <div class="booking-info-card animate__animated animate__fadeInUp">
                <div class="row">
                    <div class="col-md-4">
                        @if($booking->wisata->gambar)
                            <img src="{{ asset('storage/wisata/' . $booking->wisata->gambar) }}" 
                                 alt="{{ $booking->wisata->nama_wisata }}" 
                                 class="wisata-image">
                        @else
                            <div class="wisata-image bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-mountain fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h4 class="mb-3">{{ $booking->wisata->nama_wisata }}</h4>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <div>
                                        <strong>Tanggal Kunjungan:</strong><br>
                                        {{ \Carbon\Carbon::parse($booking->tanggal_kunjungan)->format('d F Y') }}
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <i class="fas fa-users"></i>
                                    <div>
                                        <strong>Jumlah Tiket:</strong><br>
                                        {{ $booking->jumlah_tiket }} orang
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <div>
                                        <strong>Lokasi:</strong><br>
                                        {{ $booking->wisata->lokasi }}
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <div>
                                        <strong>Total Harga:</strong><br>
                                        Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-ticket-alt"></i>
                            <div>
                                <strong>Kode Booking:</strong><br>
                                <span class="badge bg-primary">{{ $booking->kode_booking }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Ulasan -->
            <div class="card shadow-sm animate__animated animate__fadeInUp">
                <div class="card-body p-4">
                    <h4 class="card-title mb-4"><i class="fas fa-edit me-2"></i>Tulis Ulasan Anda</h4>
                    
                    <form action="{{ route('wisatawan.ulasan.store', $booking) }}" method="POST" id="reviewForm">
                        @csrf
                        
                        <!-- Rating -->
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-3">Rating <span class="text-danger">*</span></label>
                            <div class="text-center">
                                <div class="rating-stars mb-2">
                                    <input type="radio" id="star5" name="rating" value="5" required>
                                    <label for="star5" title="5 bintang"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" id="star4" name="rating" value="4">
                                    <label for="star4" title="4 bintang"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" id="star3" name="rating" value="3">
                                    <label for="star3" title="3 bintang"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" id="star2" name="rating" value="2">
                                    <label for="star2" title="2 bintang"><i class="fas fa-star"></i></label>
                                    
                                    <input type="radio" id="star1" name="rating" value="1">
                                    <label for="star1" title="1 bintang"><i class="fas fa-star"></i></label>
                                </div>
                                <div class="rating-display" id="ratingText">
                                    Pilih rating dengan mengklik bintang di atas
                                </div>
                            </div>
                            @error('rating')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Komentar -->
                        <div class="mb-4">
                            <label for="komentar" class="form-label fw-bold">Komentar <span class="text-danger">*</span></label>
                            <textarea class="form-control" 
                                      id="komentar" 
                                      name="komentar" 
                                      rows="6" 
                                      placeholder="Bagikan pengalaman Anda mengunjungi tempat ini. Apa yang Anda sukai? Apakah ada saran untuk perbaikan?"
                                      maxlength="1000"
                                      required>{{ old('komentar') }}</textarea>
                            <div class="character-count" id="charCount">
                                <span id="currentChars">0</span> / 1000 karakter
                            </div>
                            @error('komentar')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Tombol Aksi -->
                        <div class="row mt-5">
                            <div class="col-md-6 mb-2">
                                <a href="{{ route('wisatawan.bookings') }}" class="btn btn-cancel">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                            </div>
                            <div class="col-md-6 mb-2">
                                <button type="submit" class="btn-submit">
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Ulasan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Panduan Ulasan -->
            <div class="review-guidelines animate__animated animate__fadeIn">
                <h6><i class="fas fa-lightbulb me-2"></i>Tips Menulis Ulasan yang Baik</h6>
                <ul>
                    <li>Ceritakan pengalaman pribadi Anda secara objektif</li>
                    <li>Sebutkan hal-hal yang membuat Anda terkesan</li>
                    <li>Berikan saran konstruktif jika ada yang perlu diperbaiki</li>
                    <li>Hindari penggunaan kata-kata kasar atau SARA</li>
                    <li>Ulasan harus relevan dengan pengalaman Anda</li>
                    <li>Ulasan Anda akan membantu wisatawan lain dalam memutuskan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Rating stars interaction
        const stars = document.querySelectorAll('.rating-stars input');
        const ratingText = document.getElementById('ratingText');
        const ratingDescriptions = {
            1: 'Buruk - Sangat tidak merekomendasikan',
            2: 'Cukup - Ada banyak yang perlu diperbaiki',
            3: 'Baik - Pengalaman yang menyenangkan',
            4: 'Sangat Baik - Sangat merekomendasikan',
            5: 'Luar Biasa - Pengalaman terbaik!'
        };
        
        stars.forEach(star => {
            star.addEventListener('change', function() {
                const value = parseInt(this.value);
                ratingText.innerHTML = `
                    <strong>${value} Bintang</strong> - ${ratingDescriptions[value]}
                    <div class="text-warning">
                        ${'<i class="fas fa-star"></i>'.repeat(value)}
                    </div>
                `;
            });
        });
        
        // Character counter for comment
        const komentar = document.getElementById('komentar');
        const charCount = document.getElementById('charCount');
        const currentChars = document.getElementById('currentChars');
        
        komentar.addEventListener('input', function() {
            const length = this.value.length;
            currentChars.textContent = length;
            
            if (length >= 950) {
                charCount.classList.add('warning');
                charCount.innerHTML = `<span id="currentChars">${length}</span> / 1000 karakter - Hati-hati, karakter hampir habis!`;
            } else if (length >= 800) {
                charCount.classList.add('warning');
                charCount.innerHTML = `<span id="currentChars">${length}</span> / 1000 karakter`;
            } else {
                charCount.classList.remove('warning');
                charCount.innerHTML = `<span id="currentChars">${length}</span> / 1000 karakter`;
            }
        });
        
        // Initialize character count
        komentar.dispatchEvent(new Event('input'));
        
        // Form validation dengan alert biasa
        const form = document.getElementById('reviewForm');
        form.addEventListener('submit', function(e) {
            const rating = document.querySelector('input[name="rating"]:checked');
            const comment = komentar.value.trim();
            
            // Validasi rating
            if (!rating) {
                e.preventDefault();
                alert('⚠️ Rating Belum Dipilih\nSilakan berikan rating dengan mengklik bintang terlebih dahulu.');
                return false;
            }
            
            // Validasi komentar
            if (comment.length < 10) {
                e.preventDefault();
                alert('⚠️ Komentar Terlalu Pendek\nKomentar harus minimal 10 karakter.\nSilakan jelaskan pengalaman Anda lebih detail.');
                return false;
            }
            
            // Jika validasi lolos, tampilkan loading
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
            submitBtn.disabled = true;
            
            // Biarkan form submit
            return true;
        });
        
        // Jika ada error dari server, scroll ke error
        @if($errors->any())
            setTimeout(() => {
                const firstError = document.querySelector('.text-danger');
                if (firstError) {
                    firstError.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                }
            }, 300);
        @endif
    });
</script>
@endpush
