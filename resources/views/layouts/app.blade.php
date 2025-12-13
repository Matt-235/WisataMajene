<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wisata Majene @yield('title')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Animate.css untuk animasi -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding-top: 56px; /* Menyesuaikan tinggi navbar */
        }
        
        /* Menyesuaikan tinggi untuk desktop - DIPERBAIKI */
        @media (min-width: 992px) {
            body {
                padding-top: 60px; /* Dikurangi dari 70px */
            }
        }
        
        .navbar {
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            padding: 0.3rem 0; /* Dikurangi padding */
        }
        
        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
            font-size: 1.2rem; /* Sedikit diperkecil */
            padding: 0.25rem 0;
        }
        
        .hero-section {
            background: linear-gradient(rgba(44, 62, 80, 0.9), rgba(52, 152, 219, 0.8)), 
                        url('https://images.unsplash.com/photo-1469474968028-56623f02e42e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 40px 0; /* Dikurangi dari 80px */
        }
        
        .card {
            border: none;
            border-radius: 10px; /* Sedikit lebih kecil */
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            margin-bottom: 15px; /* Dikurangi */
        }
        
        .card:hover {
            transform: translateY(-5px); /* Efek lebih kecil */
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            border: none;
            padding: 8px 20px; /* Dikurangi */
            border-radius: 6px; /* Kurangi kelengkungan */
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-color);
            transform: scale(1.02);
        }
        
        .footer {
            background-color: var(--primary-color);
            color: white;
            padding: 20px 0 15px; /* Dikurangi */
            margin-top: 30px; /* Dikurangi dari 50px */
        }
        
        /* Main content adjustments - DIPERBAIKI */
        main {
            padding-top: 10px; /* Dikurangi */
            padding-bottom: 20px;
        }
        
        /* Konten dalam container */
        .container.main-content {
            padding-top: 15px;
            padding-bottom: 20px;
        }
        
        /* Alert positioning - DIPERBAIKI */
        .alert {
            margin-top: 10px; /* Dikurangi drastis dari 80px */
            margin-bottom: 15px;
            padding: 10px 15px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            body {
                padding-top: 52px;
            }
            
            .hero-section {
                padding: 30px 0;
            }
            
            .container.main-content {
                padding-top: 10px;
                padding-bottom: 15px;
            }
            
            .alert {
                margin-top: 8px;
            }
            
            .footer {
                padding: 15px 0 10px;
                margin-top: 20px;
            }
        }
        
        /* Dropdown adjustments - DIPERBAIKI */
        .dropdown-menu {
            border: 1px solid rgba(0,0,0,0.1);
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            border-radius: 6px;
            margin-top: 5px;
            padding: 5px 0;
        }
        
        .dropdown-item {
            padding: 6px 15px;
            font-size: 0.9rem;
        }
        
        /* Nav item spacing - DIPERBAIKI */
        .nav-item {
            margin: 0 2px;
        }
        
        .nav-link {
            font-weight: 500;
            padding: 6px 10px !important;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        
        /* Animation untuk konten */
        .content-animate {
            animation: fadeInUp 0.3s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Spacing untuk halaman login/register */
        .auth-container {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        /* Mengurangi spacing Bootstrap default */
        .form-group {
            margin-bottom: 1rem;
        }
        
        h1, h2, h3, h4, h5, h6 {
            margin-bottom: 0.8rem;
        }
        
        p {
            margin-bottom: 0.8rem;
        }
        
        /* Grid spacing lebih rapat */
        .row {
            margin-left: -8px;
            margin-right: -8px;
        }
        
        .col, .col-1, .col-10, .col-11, .col-12, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-auto, .col-lg, .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-auto, .col-md, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-auto, .col-sm, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-auto, .col-xl, .col-xl-1, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-auto {
            padding-left: 8px;
            padding-right: 8px;
        }
        
        /* Section spacing */
        .section {
            padding: 20px 0;
        }
        
        .section-title {
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--secondary-color);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navbar - DIPERBAIKI -->
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-mountain me-2"></i>Wisata Majene
            </a>
            <button class="navbar-toggler py-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}"><i class="fas fa-home me-1"></i>Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('wisata.index') }}"><i class="fas fa-map-marked-alt me-1"></i>Destinasi</a>
                    </li>
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-cogs me-1"></i>Admin
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="fas fa-users me-2"></i>Pengguna</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.wisata.index') }}"><i class="fas fa-map-pin me-2"></i>Wisata</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.bookings.index') }}"><i class="fas fa-calendar-check me-2"></i>Booking</a></li>
                                </ul>
                            </li>
                        @elseif(Auth::user()->role === 'pengelola_wisata')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="pengelolaDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-tie me-1"></i>Pengelola
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('pengelola.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('pengelola.wisata.index') }}"><i class="fas fa-map-marked me-2"></i>Wisata Saya</a></li>
                                    <li><a class="dropdown-item" href="{{ route('pengelola.bookings.index') }}"><i class="fas fa-receipt me-2"></i>Booking</a></li>
                                </ul>
                            </li>
                        @elseif(Auth::user()->role === 'wisatawan')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('wisatawan.bookings') }}"><i class="fas fa-calendar me-1"></i>Booking</a>
                            </li>
                        @endif
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>{{ Str::limit(Auth::user()->nama, 12) }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i>Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm py-1 px-3" href="{{ route('register') }}">Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content - DIPERBAIKI -->
    <main class="content-animate pt-2"> <!-- Padding top sangat kecil -->
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show mt-2 mb-2" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="container">
                <div class="alert alert-danger alert-dismissible fade show mt-2 mb-2" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        
        <div class="container main-content">
            @yield('content')
        </div>
    </main>

    <!-- Footer - DIPERBAIKI -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5 class="mb-2"><i class="fas fa-mountain me-2"></i>Wisata Majene</h5>
                    <p class="mb-0 small">Menjelajahi keindahan alam dan budaya Majene yang memukau.</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5 class="mb-2">Kontak Kami</h5>
                    <p class="mb-1 small"><i class="fas fa-map-marker-alt me-2"></i>Jl. Pariwisata No. 1, Majene</p>
                    <p class="mb-1 small"><i class="fas fa-phone me-2"></i>(0422) 12345</p>
                    <p class="mb-0 small"><i class="fas fa-envelope me-2"></i>info@wisatamajene.com</p>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-2">Ikuti Kami</h5>
                    <div class="social-links">
                        <a href="#" class="text-white me-2" title="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white me-2" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white me-2" title="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white" title="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr class="bg-white my-2">
            <div class="text-center">
                <p class="mb-0 small">&copy; 2024 Wisata Majene. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Inisialisasi tooltip
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Animasi saat scroll - disederhanakan
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                    }
                });
            }, { threshold: 0.1 });
            
            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                observer.observe(el);
            });
        });
        
        // Smooth scroll untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 60, // Lebih kecil offset
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>