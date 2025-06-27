<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Detail User</p>
                                <div class="ms-auto">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary me-2">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">ID</label>
                                        <p class="form-control-static">{{ $user->id }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Nama Lengkap</label>
                                        <p class="form-control-static">{{ $user->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Email</label>
                                        <p class="form-control-static">{{ $user->email }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Role</label>
                                        <p class="form-control-static">
                                            <span class="badge badge-sm
                                                @if($user->role == 'admin') bg-gradient-success
                                                @elseif($user->role == 'petugas') bg-gradient-info
                                                @else bg-gradient-warning
                                                @endif">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Departemen</label>
                                        <p class="form-control-static">{{ $user->department ?? 'Tidak ada departemen' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Telepon</label>
                                        <p class="form-control-static">{{ $user->phone ?? 'Tidak ada nomor telepon' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Tanggal Dibuat</label>
                                        <p class="form-control-static">{{ $user->created_at->format('d F Y, H:i') }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Terakhir Diupdate</label>
                                        <p class="form-control-static">{{ $user->updated_at->format('d F Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>

                            @if($user->role == 'peminjam')
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <h6>Riwayat Peminjaman</h6>
                                    @if($user->peminjaman->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Kode Arsip</th>
                                                        <th>Nama Dokumen</th>
                                                        <th>Tanggal Pinjam</th>
                                                        <th>Tanggal Kembali</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($user->peminjaman->take(5) as $pinjam)
                                                    <tr>
                                                        <td>{{ $pinjam->arsip->kode_arsip ?? '-' }}</td>
                                                        <td>{{ $pinjam->arsip->nama_dokumen ?? '-' }}</td>
                                                        <td>{{ $pinjam->tanggal_pinjam->format('d/m/Y') }}</td>
                                                        <td>{{ $pinjam->tanggal_kembali ? $pinjam->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                                                        <td>
                                                            <span class="badge badge-sm
                                                                @if($pinjam->status == 'dipinjam') bg-gradient-warning
                                                                @elseif($pinjam->status == 'dikembalikan') bg-gradient-success
                                                                @else bg-gradient-secondary
                                                                @endif">
                                                                {{ ucfirst($pinjam->status) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @if($user->peminjaman->count() > 5)
                                                <p class="text-sm text-muted">Menampilkan 5 dari {{ $user->peminjaman->count() }} peminjaman</p>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-muted">Belum ada riwayat peminjaman</p>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>
</x-app-layout>
