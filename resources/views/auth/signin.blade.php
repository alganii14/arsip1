<x-guest-layout>
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <x-guest.sidenav-guest />
            </div>
        </div>
    </div>
    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="container-fluid px-2">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-lg-6 col-md-7 col-sm-9 col-12">
                            <div class="card card-plain shadow-lg border-0" style="backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95);">
                                <div class="card-header pb-0 text-center bg-transparent">
                                    <div class="mb-2">
                                        <i class="fas fa-archive text-primary" style="font-size: clamp(1.5rem, 3.5vw, 2rem);"></i>
                                    </div>
                                    <h2 class="font-weight-bold text-primary mb-1" style="font-size: clamp(1.2rem, 2.8vw, 1.6rem);">E-Arsip</h2>
                                    <h4 class="font-weight-normal text-dark mb-1" style="font-size: clamp(0.95rem, 2.2vw, 1.2rem);">Selamat Datang Kembali</h4>
                                    <p class="text-muted mb-0" style="font-size: clamp(0.75rem, 1.6vw, 0.85rem);">Masuk ke sistem manajemen arsip digital</p>
                                </div>
                                <div class="text-center">
                                    @if (session('status'))
                                        <div class="alert alert-success text-sm" role="alert">
                                            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                                        </div>
                                    @endif
                                    @error('message')
                                        <div class="alert alert-danger text-sm" role="alert">
                                            <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="card-body px-3 py-2">
                                    <form role="form" class="text-start" method="POST" action="sign-in">
                                        @csrf
                                        <div class="mb-2">
                                            <label class="form-label text-dark font-weight-bold small">
                                                <i class="fas fa-envelope me-2 text-primary"></i>Email
                                            </label>
                                            <input type="email" id="email" name="email"
                                                class="form-control border-2"
                                                placeholder="Masukkan alamat email Anda"
                                                value="{{ old('email') ? old('email') : 'admin@corporateui.com' }}"
                                                aria-label="Email" style="border-radius: 10px;">
                                            @error('email')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label text-dark font-weight-bold small">
                                                <i class="fas fa-lock me-2 text-primary"></i>Password
                                            </label>
                                            <input type="password" id="password" name="password"
                                                value="{{ old('password') ? old('password') : 'secret' }}"
                                                class="form-control border-2"
                                                placeholder="Masukkan password Anda" aria-label="Password"
                                                style="border-radius: 10px;">
                                            <div class="form-check mt-1">
                                                <input class="form-check-input" type="checkbox" id="showPassword">
                                                <label class="form-check-label text-muted small" for="showPassword">
                                                    <i class="fas fa-check me-1"></i>Tampilkan password
                                                </label>
                                            </div>
                                            @error('password')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap">
                                            <div class="form-check form-check-primary">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="flexCheckDefault">
                                                <label class="form-check-label text-dark small" for="flexCheckDefault">
                                                    <i class="fas fa-heart me-1 text-danger"></i>Ingat saya
                                                </label>
                                            </div>

                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-2 shadow"
                                                style="border-radius: 10px; background: linear-gradient(45deg, #667eea, #764ba2);">
                                                <i class="fas fa-sign-in-alt me-2"></i>Masuk ke E-Arsip
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-1 bg-transparent">
                                    <div class="border-top pt-2">
                                        <p class="text-muted mb-1 small">Belum memiliki akun?</p>
                                        <a href="{{ route('sign-up') }}"
                                           class="btn btn-outline-primary shadow-sm"
                                           style="border-radius: 10px; padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 d-none d-lg-block">
                            <div class="position-relative h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center text-white px-3">
                                    <div class="mb-4">
                                        <i class="fas fa-file-archive" style="font-size: clamp(3rem, 8vw, 5rem); opacity: 0.8;"></i>
                                    </div>
                                    <h2 class="font-weight-bold mb-3" style="font-size: clamp(1.25rem, 3vw, 2rem);">Sistem Manajemen Arsip Digital</h2>
                                    <p class="lead mb-4" style="opacity: 0.9; font-size: clamp(0.875rem, 2vw, 1.25rem);">
                                        Kelola dan akses dokumen arsip dengan mudah, aman, dan efisien
                                    </p>
                                    <div class="d-flex justify-content-center flex-wrap">
                                        <div class="mx-2 mx-lg-3 text-center mb-3">
                                            <i class="fas fa-shield-alt fa-2x mb-2"></i>
                                            <p class="small">Keamanan Tinggi</p>
                                        </div>
                                        <div class="mx-2 mx-lg-3 text-center mb-3">
                                            <i class="fas fa-search fa-2x mb-2"></i>
                                            <p class="small">Pencarian Cepat</p>
                                        </div>
                                        <div class="mx-2 mx-lg-3 text-center mb-3">
                                            <i class="fas fa-cloud fa-2x mb-2"></i>
                                            <p class="small">Akses Online</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showPasswordCheckbox = document.getElementById('showPassword');
            const passwordInput = document.getElementById('password');

            if (showPasswordCheckbox && passwordInput) {
                showPasswordCheckbox.addEventListener('change', function() {
                    // Toggle the type attribute based on checkbox state
                    const type = this.checked ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                });
            }
        });
    </script>

    <style>
        /* Fix layout tanpa scroll */
        .page-header {
            min-height: 100vh !important;
            height: 100vh !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            overflow: hidden !important;
            padding: 10px 0 !important;
        }
        
        .container-fluid {
            width: 100% !important;
            max-width: 100% !important;
        }
        
        .card {
            max-width: 100% !important;
            width: 100% !important;
            margin: 0 auto !important;
        }
        
        /* Compact spacing */
        .form-control {
            padding: 0.5rem 0.75rem !important;
            font-size: 0.9rem !important;
        }
        
        .form-label {
            margin-bottom: 0.2rem !important;
            font-size: 0.85rem !important;
        }
        
        .btn-lg {
            padding: 0.55rem 1rem !important;
            font-size: 0.9rem !important;
        }
        
        /* Header adjustments */
        .card-header {
            padding: 0.75rem 1rem 0.5rem !important;
        }
        
        .card-body {
            padding: 0.75rem 1rem !important;
        }
        
        .card-footer {
            padding: 0.5rem 1rem !important;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .page-header {
                padding: 5px 0 !important;
            }
            
            .card-header {
                padding: 0.5rem 1rem 0.3rem !important;
            }
            
            .card-body {
                padding: 0.5rem 1rem !important;
            }
            
            .card-footer {
                padding: 0.3rem 1rem !important;
            }
            
            .form-control {
                padding: 0.45rem 0.65rem !important;
                font-size: 0.85rem !important;
            }
            
            .form-label {
                font-size: 0.8rem !important;
            }
            
            .btn-lg {
                padding: 0.5rem 0.9rem !important;
                font-size: 0.85rem !important;
            }
            
            .d-flex.justify-content-between {
                flex-direction: column !important;
                align-items: flex-start !important;
            }
        }
        
        @media (max-width: 576px) {
            .container-fluid {
                padding: 0.3rem !important;
            }
            
            .card-header {
                padding: 0.4rem 0.8rem 0.2rem !important;
            }
            
            .card-body {
                padding: 0.4rem 0.8rem !important;
            }
            
            .card-footer {
                padding: 0.2rem 0.8rem !important;
            }
            
            .form-control {
                padding: 0.4rem 0.6rem !important;
                font-size: 0.8rem !important;
            }
            
            .form-label {
                font-size: 0.75rem !important;
            }
            
            .btn {
                padding: 0.45rem 0.8rem !important;
                font-size: 0.8rem !important;
            }
            
            .d-flex.flex-wrap > div {
                margin-bottom: 0.2rem !important;
            }
        }
        
        /* Prevent scroll */
        body, html {
            overflow-x: hidden !important;
            overflow-y: hidden !important;
        }
        
        .main-content {
            width: 100% !important;
            overflow: hidden !important;
        }
        
        /* Smooth transitions */
        .form-control, .btn {
            transition: all 0.3s ease;
        }
        
        /* Better mobile touch targets */
        @media (hover: none) and (pointer: coarse) {
            .form-check-input {
                transform: scale(1.1);
            }
            
            .form-check-label {
                padding-left: 0.3rem;
            }
        }
        
        /* Fix untuk layar kecil agar tidak terpotong */
        @media (max-height: 700px) {
            .card-header {
                padding: 0.5rem 1rem 0.2rem !important;
            }
            
            .card-body {
                padding: 0.5rem 1rem !important;
            }
            
            .card-footer {
                padding: 0.2rem 1rem !important;
            }
            
            .mb-2 {
                margin-bottom: 0.5rem !important;
            }
            
            .mb-1 {
                margin-bottom: 0.25rem !important;
            }
        }
        
        /* Extra compact untuk layar sangat kecil */
        @media (max-height: 600px) {
            .card-header {
                padding: 0.3rem 1rem 0.1rem !important;
            }
            
            .card-body {
                padding: 0.3rem 1rem !important;
            }
            
            .card-footer {
                padding: 0.1rem 1rem !important;
            }
            
            .form-control {
                padding: 0.3rem 0.5rem !important;
                font-size: 0.75rem !important;
            }
            
            .btn {
                padding: 0.3rem 0.6rem !important;
                font-size: 0.75rem !important;
            }
        }
    </style>

</x-guest-layout>
