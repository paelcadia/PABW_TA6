@extends('layouts.app')

@section('title', 'Kirim Laporan | LaporBanjir')

@section('content')
<section class="report-intro">
    <p class="eyebrow"><span class="live-dot"></span> Kanal pelaporan warga</p>
    <h1>Air naik?<br><span>Laporkan di sini.</span></h1>
    <p class="intro-copy">Informasikan kondisi genangan di sekitar Anda. Petugas BPBD Kabupaten Bandung akan menggunakan laporan ini untuk memantau situasi.</p>
    <div class="intro-meta"><span class="meta-rule"></span><span>Formulir terbuka untuk seluruh warga</span></div>
</section>

<section class="form-section" aria-labelledby="form-heading">
    <div class="section-heading">
        <div><p class="eyebrow">01 <span class="eyebrow-divider">/</span> Data kejadian</p><h2 id="form-heading">{{ ($management ?? false) ? 'Tambah laporan' : 'Buat laporan' }}</h2></div>
        <span class="required-note">Semua kolom wajib diisi</span>
    </div>
    <form method="POST" action="{{ ($management ?? false) ? route('laporan.store') : route('lapor.store') }}" enctype="multipart/form-data" class="report-form">
        @csrf
        @include('laporan._fields')
        <div class="form-actions">
            <p>Foto bukti harus berformat JPG, JPEG, atau PNG dengan ukuran maksimal 2 MB.</p>
            <button class="button button-primary" type="submit">Kirim laporan <span aria-hidden="true">→</span></button>
        </div>
    </form>
</section>
<section class="public-note"><span class="note-icon" aria-hidden="true">i</span><p>Warga dapat mengirim laporan tanpa akun. Data laporan hanya dapat dikelola oleh petugas yang sudah login.</p></section>
@endsection
