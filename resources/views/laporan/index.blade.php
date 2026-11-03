@extends('layouts.app')

@section('title', 'Daftar Laporan | LaporBanjir')

@section('content')
<section class="page-heading">
    <div><p class="eyebrow">Ruang petugas <span class="eyebrow-divider">/</span> Data masuk</p><h1>Daftar laporan</h1><p class="heading-copy">Pantau laporan kejadian banjir yang dikirim warga Kabupaten Bandung.</p></div>
    <div class="count-block"><strong>{{ number_format($jumlahLaporan) }}</strong><span>laporan tercatat</span></div>
</section>

<section class="table-section" aria-label="Tabel laporan banjir">
    <div class="table-toolbar"><span class="table-label">Laporan terbaru</span><span class="table-hint">Diperbarui saat halaman dimuat</span></div>
    <div class="table-scroll">
        <table>
            <thead><tr><th>No.</th><th>Pelapor</th><th>Lokasi kejadian</th><th>Tinggi air</th><th>Tanggal</th></tr></thead>
            <tbody>
                @forelse ($laporan as $item)
                    <tr>
                        <td class="row-number">{{ $laporan->firstItem() + $loop->index }}</td>
                        <td class="reporter-name">{{ $item->nama_pelapor }}</td>
                        <td>{{ $item->lokasi }}</td>
                        <td><span class="water-level">{{ number_format($item->tinggi_genangan) }} <small>cm</small></span></td>
                        <td class="date-cell">{{ $item->tanggal_kejadian->translatedFormat('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-state">Belum ada laporan masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($laporan->hasPages())<div class="pagination-wrap">{{ $laporan->links() }}</div>@endif
</section>
<p class="privacy-caption"><span class="privacy-mark" aria-hidden="true">●</span> Data pelapor ditampilkan untuk keperluan penanganan petugas BPBD.</p>
@endsection