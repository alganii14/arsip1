<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card">
                        <div class="pb-0 card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h5 class="">Manajemen User</h5>
                                    <p class="mb-0 text-sm">
                                        Kelola semua user di sistem arsip
                                    </p>
                                </div>
                                <div class="col-6 text-end">
                                    <a href="{{ route('users.create') }}" class="btn btn-dark btn-primary">
                                        <i class="fas fa-user-plus me-2"></i> Tambah User
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">
                                @if (session('success'))
                                    <div class="alert alert-success" role="alert" id="alert">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger" role="alert" id="alert">
                                        {{ session('error') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table text-secondary text-center" id="datatable-search">
                                <thead>
                                    <tr>
                                        <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            ID</th>
                                        <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Nama</th>
                                        <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Email</th>
                                        <th class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Role</th>
                                        <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Departemen</th>
                                        <th class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Telepon</th>
                                        <th class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Tanggal Dibuat</th>
                                        <th class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                    <tr>
                                        <td class="align-middle bg-transparent border-bottom">{{ $user->id }}</td>
                                        <td class="align-middle bg-transparent border-bottom text-left">{{ $user->name }}</td>
                                        <td class="align-middle bg-transparent border-bottom text-left">{{ $user->email }}</td>
                                        <td class="text-center align-middle bg-transparent border-bottom">
                                            <span class="badge badge-sm
                                                @if($user->role == 'unit_kerja') bg-gradient-success
                                                @elseif($user->role == 'unit_pengelola') bg-gradient-info
                                                @elseif($user->role == 'admin') bg-gradient-success
                                                @elseif($user->role == 'petugas') bg-gradient-info
                                                @else bg-gradient-warning
                                                @endif">
                                                @if($user->role === 'unit_kerja')
                                                    Unit Kerja
                                                @elseif($user->role === 'unit_pengelola')
                                                    Unit Pengelola
                                                @else
                                                    {{ ucfirst($user->role) }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="align-middle bg-transparent border-bottom text-left">
                                            {{ $user->department ?? '-' }}
                                        </td>
                                        <td class="align-middle bg-transparent border-bottom text-left">
                                            {{ $user->phone ?? '-' }}
                                        </td>
                                        <td class="text-center align-middle bg-transparent border-bottom">
                                            {{ $user->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="text-center align-middle bg-transparent border-bottom">
                                            <a href="{{ route('users.show', $user) }}" class="text-secondary font-weight-bold text-xs me-2"
                                               data-toggle="tooltip" data-original-title="Lihat detail">
                                                <i class="fas fa-eye" aria-hidden="true"></i>
                                            </a>
                                            <a href="{{ route('users.edit', $user) }}" class="text-secondary font-weight-bold text-xs me-2"
                                               data-toggle="tooltip" data-original-title="Edit user">
                                                <i class="fas fa-user-edit" aria-hidden="true"></i>
                                            </a>
                                            @if(auth()->user()->isAdmin() && $user->id !== auth()->id())
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-secondary font-weight-bold text-xs border-0 bg-transparent"
                                                        data-toggle="tooltip" data-original-title="Hapus user">
                                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center align-middle bg-transparent border-bottom">
                                            Tidak ada data user yang tersedia
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>
</x-app-layout>

<script src="/assets/js/plugins/datatables.js"></script>
<script>
    const dataTableBasic = new simpleDatatables.DataTable("#datatable-search", {
        searchable: true,
        fixedHeight: true,
        columns: [{
            select: [7],
            sortable: false
        }]
    });

    // Auto hide alerts after 5 seconds
    setTimeout(function() {
        const alert = document.getElementById('alert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 5000);
</script>
