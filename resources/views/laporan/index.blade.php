@extends('layouts.app')

@section('title', 'Daftar Laporan | LaporBanjir')

@section('content')
<section class="page-heading">
    <div><p class="eyebrow">Ruang petugas <span class="eyebrow-divider">/</span> Data masuk</p><h1>Daftar laporan</h1><p class="heading-copy">Pantau laporan kejadian banjir yang dikirim warga Kabupaten Bandung.</p></div>
    <div class="count-block"><strong>{{ number_format($jumlahLaporan) }}</strong><span>laporan tercatat</span></div>
</section>

<section class="table-section" aria-label="Tabel laporan banjir">
    <div class="table-toolbar"><span class="table-label">Laporan terbaru</span><a class="table-action" href="{{ route('laporan.create') }}">Tambah laporan <span aria-hidden="true">+</span></a></div>
    <div class="table-scroll">
        <table>
            <thead><tr><th>No.</th><th>Foto</th><th>Pelapor</th><th>Lokasi kejadian</th><th>Tinggi air</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse ($laporan as $item)
                    <tr>
                        <td class="row-number">{{ $laporan->firstItem() + $loop->index }}</td>
                        <td>
                            @if ($item->foto)
                                <img class="table-photo" src="{{ asset('storage/'.$item->foto) }}" alt="Foto banjir oleh {{ $item->nama_pelapor }}">
                            @else
                                <span class="table-hint">Tidak ada foto</span>
                            @endif
                        </td>
                        <td class="reporter-name">{{ $item->nama_pelapor }}</td>
                        <td>{{ $item->lokasi }}<br><span class="table-hint">{{ $item->kecamatan ?? '' }}</span></td>
                        <td><span class="water-level">{{ number_format($item->tinggi_air ?? $item->tinggi_genangan) }} <small>cm</small></span></td>
                        <td><span class="status-badge">{{ $item->status }}</span></td>
                        <td class="row-actions">
                            <a href="{{ route('laporan.show', $item) }}">Detail</a>
                            <a href="{{ route('laporan.edit', $item) }}">Ubah</a>
                            <form method="POST" action="{{ route('laporan.destroy', $item) }}" onsubmit="return confirm('Hapus laporan ini beserta foto bukti?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty-state">Belum ada laporan masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($laporan->hasPages())<div class="pagination-wrap">{{ $laporan->links() }}</div>@endif
</section>
<p class="privacy-caption"><span class="privacy-mark" aria-hidden="true">●</span> Data pelapor ditampilkan untuk keperluan penanganan petugas BPBD.</p>
@endsection