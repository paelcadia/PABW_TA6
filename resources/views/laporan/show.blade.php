@extends('layouts.app')

@section('title', 'Detail Laporan | LaporBanjir')

@section('content')
<section class="page-heading">
    <div><p class="eyebrow">Ruang petugas <span class="eyebrow-divider">/</span> Detail laporan</p><h1>Detail laporan</h1><p class="heading-copy">Laporan #{{ $laporan->id }} dari {{ $laporan->nama_pelapor }}.</p></div>
    <div class="detail-actions">
        <a class="button button-primary" href="{{ route('laporan.edit', $laporan) }}">Ubah laporan</a>
        <a class="button button-secondary" href="{{ route('laporan.index') }}">Kembali</a>
    </div>
</section>

<section class="detail-sheet">
    <dl class="detail-grid">
        <div><dt>Nama pelapor</dt><dd>{{ $laporan->nama_pelapor }}</dd></div>
        <div><dt>Nomor HP</dt><dd>{{ $laporan->no_hp ?? '—' }}</dd></div>
        <div><dt>Lokasi</dt><dd>{{ $laporan->lokasi }}</dd></div>
        <div><dt>Kecamatan</dt><dd>{{ $laporan->kecamatan ?? '—' }}</dd></div>
        <div><dt>Tinggi air</dt><dd>{{ $laporan->tinggi_air ?? $laporan->tinggi_genangan }} cm</dd></div>
        <div><dt>Status penanganan</dt><dd><span class="status-badge">{{ $laporan->status }}</span></dd></div>
        <div class="detail-wide"><dt>Keterangan</dt><dd>{{ $laporan->keterangan ?? '—' }}</dd></div>
        <div class="detail-wide">
            <dt>Foto bukti</dt>
            <dd>
                @if ($laporan->foto)
                    <a href="{{ asset('storage/'.$laporan->foto) }}" target="_blank" rel="noopener"><img class="detail-photo" src="{{ asset('storage/'.$laporan->foto) }}" alt="Foto bukti laporan"></a>
                @else
                    —
                @endif
            </dd>
        </div>
    </dl>
</section>
@endsection
