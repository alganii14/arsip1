<table>
    <thead>
        <tr>
            <th colspan="9" style="text-align: center; font-size: 16px; font-weight: bold;">LAPORAN RIWAYAT PEMUSNAHAN ARSIP</th>
        </tr>
        @if($destructions->count() > 0)
            @php
                $unitKerjaList = $destructions->map(function($d) {
                    return $d->user->department ?? $d->user->name ?? 'N/A';
                })->unique()->filter(function($value) {
                    return $value !== 'N/A';
                })->values();
            @endphp
            @if($unitKerjaList->count() > 0)
                <tr>
                    <th colspan="9" style="text-align: center; font-size: 12px; font-style: italic;">
                        Dimusnahkan oleh: {{ $unitKerjaList->implode(', ') }}
                    </th>
                </tr>
            @endif
        @endif
        <tr>
            <th colspan="9" style="text-align: center; font-size: 12px;">
                Tanggal Export: {{ now()->format('d-m-Y H:i:s') }}
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>Kode Arsip</th>
            <th>Nama Dokumen</th>
            <th>Kategori</th>
            <th>Tanggal Arsip</th>
            <th>Tanggal Pemusnahan</th>
            <th>Petugas</th>
            <th>Catatan Pemusnahan</th>
            <th>Catatan Lengkap</th>
        </tr>
    </thead>
    <tbody>
        @forelse($destructions as $index => $destruction)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $destruction->arsip->kode }}</td>
            <td>{{ $destruction->arsip->nama_dokumen }}</td>
            <td>{{ $destruction->arsip->kategori }}</td>
            <td>{{ $destruction->arsip->tanggal_arsip ? $destruction->arsip->tanggal_arsip->format('d/m/Y') : '-' }}</td>
            <td>{{ $destruction->destroyed_at->format('d/m/Y H:i') }}</td>
            <td>{{ $destruction->user->name }}</td>
            <td>{{ Str::limit($destruction->destruction_notes, 100) }}</td>
            <td>{{ $destruction->destruction_notes }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="9" style="text-align: center;">Tidak ada data pemusnahan arsip</td>
        </tr>
        @endforelse
    </tbody>
</table>
