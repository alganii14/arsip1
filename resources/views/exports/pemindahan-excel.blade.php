<table>
    <thead>
        <tr>
            <th colspan="8" style="text-align: center; font-size: 16px; font-weight: bold;">LAPORAN PEMINDAHAN ARSIP</th>
        </tr>
        @if($pemindahans->count() > 0)
            @php
                $unitKerjaList = $pemindahans->map(function($p) {
                    return $p->user->department ?? $p->user->name ?? 'N/A';
                })->unique()->filter(function($value) {
                    return $value !== 'N/A';
                })->values();
            @endphp
            @if($unitKerjaList->count() > 0)
                <tr>
                    <th colspan="8" style="text-align: center; font-size: 12px; font-style: italic;">
                        Dipindahkan oleh: {{ $unitKerjaList->implode(', ') }}
                    </th>
                </tr>
            @endif
        @endif
        <tr>
            <th colspan="8" style="text-align: center; font-size: 12px;">
                Tanggal Export: {{ now()->format('d-m-Y H:i:s') }}
                @if($search)
                    - Filter: "{{ $search }}"
                @endif
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>Kode Arsip</th>
            <th>Nama Arsip</th>
            <th>Tahun</th>
            <th>Jumlah</th>
            <th>Tingkat Perkembangan</th>
            <th>Keterangan</th>
            <th>Tanggal Pemindahan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pemindahans as $index => $pemindahan)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $pemindahan->arsip->kode ?? $pemindahan->arsip->nomor_dokumen ?? 'N/A' }}</td>
            <td>{{ $pemindahan->arsip->nama_dokumen }}</td>
            <td>{{ $pemindahan->arsip->tanggal_arsip ? \Carbon\Carbon::parse($pemindahan->arsip->tanggal_arsip)->format('Y') : 'N/A' }}</td>
            <td>{{ $pemindahan->jumlah_folder }} folder</td>
            <td>{{ $pemindahan->tingkat_perkembangan_text }}</td>
            <td>{{ $pemindahan->keterangan }}</td>
            <td>{{ $pemindahan->created_at->format('d-m-Y') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="8" style="text-align: center;">Tidak ada data pemindahan arsip</td>
        </tr>
        @endforelse
    </tbody>
</table>
