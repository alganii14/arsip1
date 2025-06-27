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
                    <div class="row justify-content-center align-items-center min-vh-100">
                        <div class="col-lg-8 col-md-10 col-12">
                            <div class="card card-plain shadow-lg border-0" style="backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95);">
                                <div class="card-header pb-0 text-center bg-transparent">
                                    <div class="mb-3">
                                        <i class="fas fa-user-plus text-primary" style="font-size: 2.5rem;"></i>
                                    </div>
                                    <h2 class="font-weight-bold text-primary mb-2">Daftar Akun E-Arsip</h2>
                                    <p class="text-muted mb-0">Lengkapi formulir di bawah untuk membuat akun</p>
                                </div>
                                <div class="card-body px-4">
                                    <form role="form" method="POST" action="sign-up">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label text-dark font-weight-bold">
                                                        <i class="fas fa-user me-2 text-primary"></i>Nama Lengkap
                                                    </label>
                                                    <input type="text" id="name" name="name"
                                                        class="form-control form-control-lg border-2"
                                                        placeholder="Masukkan nama lengkap"
                                                        value="{{old("name")}}" aria-label="Name"
                                                        style="border-radius: 10px;">
                                                    @error('name')
                                                        <span class="text-danger text-sm">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label text-dark font-weight-bold">
                                                        <i class="fas fa-envelope me-2 text-primary"></i>Email
                                                    </label>
                                                    <input type="email" id="email" name="email"
                                                        class="form-control form-control-lg border-2"
                                                        placeholder="Masukkan alamat email"
                                                        value="{{old("email")}}" aria-label="Email"
                                                        style="border-radius: 10px;">
                                                    @error('email')
                                                        <span class="text-danger text-sm">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label text-dark font-weight-bold">
                                                        <i class="fas fa-phone me-2 text-primary"></i>Nomor Telepon
                                                    </label>
                                                    <input type="text" id="phone" name="phone"
                                                        class="form-control form-control-lg border-2"
                                                        placeholder="Masukkan nomor telepon"
                                                        value="{{old("phone")}}" aria-label="Phone"
                                                        style="border-radius: 10px;">
                                                    @error('phone')
                                                        <span class="text-danger text-sm">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label text-dark font-weight-bold">
                                                        <i class="fas fa-building me-2 text-primary"></i>Seksi/Departemen
                                                    </label>
                                                    <select id="department" name="department"
                                                        class="form-select form-select-lg border-2"
                                                        style="border-radius: 10px;">
                                                        <option value="" disabled selected>Pilih Seksi/Departemen</option>
                                                        <option value="Seksi Kesejahteraan Sosial" {{ old('department') == 'Seksi Kesejahteraan Sosial' ? 'selected' : '' }}>Seksi Kesejahteraan Sosial</option>
                                                        <option value="Seksi Pemberdayaan Masyarakat" {{ old('department') == 'Seksi Pemberdayaan Masyarakat' ? 'selected' : '' }}>Seksi Pemberdayaan Masyarakat</option>
                                                        <option value="Seksi Pemerintahan" {{ old('department') == 'Seksi Pemerintahan' ? 'selected' : '' }}>Seksi Pemerintahan</option>
                                                        <option value="Seksi Ekonomi Pembangunan dan Lingkungan Hidup" {{ old('department') == 'Seksi Ekonomi Pembangunan dan Lingkungan Hidup' ? 'selected' : '' }}>Seksi Ekonomi Pembangunan dan Lingkungan Hidup</option>
                                                        <option value="Seksi Ketentraman/Ketertiban" {{ old('department') == 'Seksi Ketentraman/Ketertiban' ? 'selected' : '' }}>Seksi Ketentraman/Ketertiban</option>
                                                        <option value="Sekretariat Kepegawaian dan Umum" {{ old('department') == 'Sekretariat Kepegawaian dan Umum' ? 'selected' : '' }}>Sekretariat Kepegawaian dan Umum</option>
                                                        <option value="Sekretariat Program Keuangan" {{ old('department') == 'Sekretariat Program Keuangan' ? 'selected' : '' }}>Sekretariat Program Keuangan</option>
                                                    </select>
                                                    @error('department')
                                                        <span class="text-danger text-sm">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label text-dark font-weight-bold">
                                                        <i class="fas fa-lock me-2 text-primary"></i>Password
                                                    </label>
                                                    <input type="password" id="password" name="password"
                                                        class="form-control form-control-lg border-2"
                                                        placeholder="Buat password" aria-label="Password"
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
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label text-dark font-weight-bold">
                                                        <i class="fas fa-key me-2 text-primary"></i>Konfirmasi Password
                                                    </label>
                                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                                        class="form-control form-control-lg border-2"
                                                        placeholder="Konfirmasi password" aria-label="Password Confirmation"
                                                        style="border-radius: 10px;">
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" id="showPasswordConfirmation">
                                                        <label class="form-check-label text-muted small" for="showPasswordConfirmation">
                                                            <i class="fas fa-check me-1"></i>Tampilkan konfirmasi password
                                                        </label>
                                                    </div>
                                                    @error('password_confirmation')
                                                        <span class="text-danger text-sm">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <div class="form-check form-check-primary d-flex align-items-center">
                                                <input class="form-check-input me-2" type="checkbox" name="terms"
                                                    id="terms" required>
                                                <label class="form-check-label text-dark" for="terms">
                                                    <i class="fas fa-check-circle me-1 text-success"></i>
                                                    Saya menyetujui <a href="javascript:;" class="text-primary font-weight-bold text-decoration-underline">Syarat dan Ketentuan</a>
                                                </label>
                                            </div>
                                            @error('terms')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3 shadow"
                                                style="border-radius: 10px; background: linear-gradient(45deg, #667eea, #764ba2);">
                                                <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-3 pb-4 bg-transparent">
                                    <div class="border-top pt-3">
                                        <p class="text-muted mb-2">Sudah memiliki akun?</p>
                                        <a href="{{ route('sign-in') }}"
                                           class="btn btn-outline-primary btn-lg shadow-sm"
                                           style="border-radius: 10px;">
                                            <i class="fas fa-sign-in-alt me-2"></i>Masuk Sekarang
                                        </a>
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
            // Toggle untuk password
            const showPasswordCheckbox = document.getElementById('showPassword');
            const passwordInput = document.getElementById('password');

            if (showPasswordCheckbox && passwordInput) {
                showPasswordCheckbox.addEventListener('change', function() {
                    const type = this.checked ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                });
            }

            // Toggle untuk konfirmasi password
            const showPasswordConfirmationCheckbox = document.getElementById('showPasswordConfirmation');
            const passwordConfirmationInput = document.getElementById('password_confirmation');

            if (showPasswordConfirmationCheckbox && passwordConfirmationInput) {
                showPasswordConfirmationCheckbox.addEventListener('change', function() {
                    const type = this.checked ? 'text' : 'password';
                    passwordConfirmationInput.setAttribute('type', type);
                });
            }
        });
    </script>

</x-guest-layout>
