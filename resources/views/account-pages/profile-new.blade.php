<x-app-layout>
    <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
        <div class="pt-7 pb-6 bg-cover"
            style="background-image: url('../assets/img/header-orange-purple.jpg'); background-position: bottom;">
        </div>
        <div class="container">
            <div class="card card-body py-2 bg-transparent shadow-none">
                <div class="row">
                    <div class="col-auto">
                        <div
                            class="avatar avatar-2xl rounded-circle position-relative mt-n7 border border-gray-100 border-4">
                            <img src="../assets/img/team-2.jpg" alt="profile_image" class="w-100">
                        </div>
                    </div>
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h3 class="mb-0 font-weight-bold">
                                {{ $user->name }}
                            </h3>
                            <p class="mb-0">
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container my-3 py-3">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Profile Information Card -->
                <div class="col-12 col-xl-6 mb-4">
                    <div class="card border shadow-xs h-100">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0 font-weight-semibold text-lg">Informasi Profile</h6>
                            <p class="text-sm mb-1">Informasi tentang akun Anda.</p>
                        </div>
                        <div class="card-body p-3">
                            <ul class="list-group">
                                <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pt-0 pb-1 text-sm">
                                    <span class="text-secondary">Nama:</span> &nbsp; {{ $user->name }}
                                </li>
                                <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm">
                                    <span class="text-secondary">Email:</span> &nbsp; {{ $user->email }}
                                </li>
                                <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm">
                                    <span class="text-secondary">Role:</span> &nbsp; {{ ucfirst($user->role) }}
                                </li>
                                <li class="list-group-item border-0 ps-0 text-dark font-weight-semibold pb-1 text-sm">
                                    <span class="text-secondary">Bergabung:</span> &nbsp; {{ $user->created_at->format('d M Y') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Change Password Card -->
                <div class="col-12 col-xl-6 mb-4">
                    <div class="card border shadow-xs h-100">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0 font-weight-semibold text-lg">Ganti Password</h6>
                            <p class="text-sm mb-1">Ubah password akun Anda untuk keamanan.</p>
                        </div>
                        <div class="card-body p-3">
                            <form action="{{ route('profile.change-password') }}" method="POST">
                                @csrf

                                <!-- Current Password -->
                                <div class="form-group mb-3">
                                    <label for="current_password" class="form-label">Password Saat Ini</label>
                                    <input type="password"
                                           class="form-control @error('current_password') is-invalid @enderror"
                                           id="current_password"
                                           name="current_password"
                                           placeholder="Masukkan password saat ini">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- New Password -->
                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">Password Baru</label>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           placeholder="Masukkan password baru">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        Password harus minimal 8 karakter dengan kombinasi huruf besar, kecil, angka, dan simbol.
                                    </small>
                                </div>

                                <!-- Confirm Password -->
                                <div class="form-group mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           placeholder="Konfirmasi password baru">
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-key me-2"></i>Ganti Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
