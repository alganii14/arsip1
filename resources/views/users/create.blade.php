<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Tambah User Baru</p>
                                <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary ms-auto">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> Ada beberapa masalah dengan input Anda.<br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('users.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-control-label">Nama Lengkap</label>
                                            <input class="form-control" type="text" name="name" id="name"
                                                   value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="text-danger text-sm">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-control-label">Email</label>
                                            <input class="form-control" type="email" name="email" id="email"
                                                   value="{{ old('email') }}" required>
                                            @error('email')
                                                <div class="text-danger text-sm">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role" class="form-control-label">Role</label>
                                            <select class="form-control" name="role" id="role" required onchange="toggleDepartment()">
                                                <option value="">Pilih Role</option>
                                                <option value="unit_kerja" {{ old('role') == 'unit_kerja' ? 'selected' : '' }}>Unit Kerja (Admin - Full Access)</option>
                                                <option value="unit_pengelola" {{ old('role') == 'unit_pengelola' ? 'selected' : '' }}>Unit Pengelola (Peminjam)</option>
                                            </select>
                                            @error('role')
                                                <div class="text-danger text-sm">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group" id="department-group" style="display: none;">
                                            <label for="department" class="form-control-label">Departemen</label>
                                            <select class="form-control" name="department" id="department">
                                                <option value="">Pilih Departemen</option>
                                                @foreach($departments as $dept)
                                                    <option value="{{ $dept }}" {{ old('department') == $dept ? 'selected' : '' }}>
                                                        {{ $dept }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('department')
                                                <div class="text-danger text-sm">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="form-control-label">Telepon</label>
                                            <input class="form-control" type="text" name="phone" id="phone"
                                                   value="{{ old('phone') }}">
                                            @error('phone')
                                                <div class="text-danger text-sm">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password" class="form-control-label">Password</label>
                                            <input class="form-control" type="password" name="password" id="password" required>
                                            @error('password')
                                                <div class="text-danger text-sm">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation" class="form-control-label">Konfirmasi Password</label>
                                            <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-dark btn-primary me-2">
                                        <i class="fas fa-save me-1"></i> Simpan
                                    </button>
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i> Batal
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>
</x-app-layout>

<script>
function toggleDepartment() {
    const role = document.getElementById('role').value;
    const departmentGroup = document.getElementById('department-group');
    const departmentSelect = document.getElementById('department');

    if (role === 'unit_pengelola') {
        departmentGroup.style.display = 'block';
        departmentSelect.required = true;
    } else {
        departmentGroup.style.display = 'none';
        departmentSelect.required = false;
        departmentSelect.value = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleDepartment();
});
</script>
