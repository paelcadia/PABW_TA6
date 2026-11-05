@extends('layouts.app')

@section('title', 'Ubah Laporan | LaporBanjir')

@section('content')
<section class="page-heading">
    <div><p class="eyebrow">Ruang petugas <span class="eyebrow-divider">/</span> Ubah data</p><h1>Ubah laporan</h1><p class="heading-copy">Perbarui informasi kejadian, foto bukti, atau status penanganan.</p></div>
</section>

<section class="form-section" aria-labelledby="form-heading">
    <div class="section-heading"><div><p class="eyebrow">ID {{ $laporan->id }}</p><h2 id="form-heading">Detail laporan</h2></div></div>
    <form method="POST" action="{{ route('laporan.update', $laporan) }}" enctype="multipart/form-data" class="report-form">
        @csrf
        @method('PUT')
        @include('laporan._fields', ['management' => true])
        <div class="form-actions">
            <a class="button button-secondary" href="{{ route('laporan.show', $laporan) }}">Batal</a>
            <button class="button button-primary" type="submit">Simpan perubahan <span aria-hidden="true">→</span></button>
        </div>
    </form>
</section>
@endsection
