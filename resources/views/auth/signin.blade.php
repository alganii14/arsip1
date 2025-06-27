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
            <div class="page-header min-vh-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-7 d-flex flex-column mx-auto">
                            <div class="card card-plain mt-5 shadow-lg border-0" style="backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95);">
                                <div class="card-header pb-0 text-center bg-transparent">
                                    <div class="mb-4">
                                        <i class="fas fa-archive text-primary" style="font-size: 3rem;"></i>
                                    </div>
                                    <h2 class="font-weight-bold text-primary mb-2">E-Arsip</h2>
                                    <h4 class="font-weight-normal text-dark mb-1">Selamat Datang Kembali</h4>
                                    <p class="text-muted mb-0">Masuk ke sistem manajemen arsip digital</p>
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
                                <div class="card-body px-4">
                                    <form role="form" class="text-start" method="POST" action="sign-in">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label text-dark font-weight-bold">
                                                <i class="fas fa-envelope me-2 text-primary"></i>Email
                                            </label>
                                            <input type="email" id="email" name="email"
                                                class="form-control form-control-lg border-2"
                                                placeholder="Masukkan alamat email Anda"
                                                value="{{ old('email') ? old('email') : 'admin@corporateui.com' }}"
                                                aria-label="Email" style="border-radius: 10px;">
                                            @error('email')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-dark font-weight-bold">
                                                <i class="fas fa-lock me-2 text-primary"></i>Password
                                            </label>
                                            <input type="password" id="password" name="password"
                                                value="{{ old('password') ? old('password') : 'secret' }}"
                                                class="form-control form-control-lg border-2"
                                                placeholder="Masukkan password Anda" aria-label="Password"
                                                style="border-radius: 10px;">
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" id="showPassword">
                                                <label class="form-check-label text-muted small" for="showPassword">
                                                    <i class="fas fa-check me-1"></i>Tampilkan password
                                                </label>
                                            </div>
                                            @error('password')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-4">
                                            <div class="form-check form-check-primary">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="flexCheckDefault">
                                                <label class="form-check-label text-dark" for="flexCheckDefault">
                                                    <i class="fas fa-heart me-1 text-danger"></i>Ingat saya
                                                </label>
                                            </div>

                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3 shadow"
                                                style="border-radius: 10px; background: linear-gradient(45deg, #667eea, #764ba2);">
                                                <i class="fas fa-sign-in-alt me-2"></i>Masuk ke E-Arsip
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-3 pb-4 bg-transparent">
                                    <div class="border-top pt-3">
                                        <p class="text-muted mb-2">Belum memiliki akun?</p>
                                        <a href="{{ route('sign-up') }}"
                                           class="btn btn-outline-primary btn-lg shadow-sm"
                                           style="border-radius: 10px;">
                                            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 d-none d-lg-block">
                            <div class="position-relative h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center text-white">
                                    <div class="mb-4">
                                        <i class="fas fa-file-archive" style="font-size: 5rem; opacity: 0.8;"></i>
                                    </div>
                                    <h2 class="font-weight-bold mb-3">Sistem Manajemen Arsip Digital</h2>
                                    <p class="lead mb-4" style="opacity: 0.9;">
                                        Kelola dan akses dokumen arsip dengan mudah, aman, dan efisien
                                    </p>
                                    <div class="d-flex justify-content-center">
                                        <div class="mx-3 text-center">
                                            <i class="fas fa-shield-alt fa-2x mb-2"></i>
                                            <p class="small">Keamanan Tinggi</p>
                                        </div>
                                        <div class="mx-3 text-center">
                                            <i class="fas fa-search fa-2x mb-2"></i>
                                            <p class="small">Pencarian Cepat</p>
                                        </div>
                                        <div class="mx-3 text-center">
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

</x-guest-layout>
