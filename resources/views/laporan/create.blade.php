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
        <div><p class="eyebrow">01 <span class="eyebrow-divider">/</span> Data kejadian</p><h2 id="form-heading">Buat laporan</h2></div>
        <span class="required-note">Semua kolom wajib diisi</span>
    </div>
    <form method="POST" action="{{ route('lapor.store') }}" class="report-form">
        @csrf
        <div class="field-grid">
            <div class="field field-wide">
                <label for="nama_pelapor">Nama pelapor</label>
                <input id="nama_pelapor" name="nama_pelapor" value="{{ old('nama_pelapor') }}" autocomplete="name" required maxlength="255" placeholder="Nama lengkap">
                @error('nama_pelapor')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field field-wide">
                <label for="lokasi">Lokasi kejadian</label>
                <input id="lokasi" name="lokasi" value="{{ old('lokasi') }}" required maxlength="255" placeholder="Kecamatan, desa, atau alamat yang mudah dikenali">
                @error('lokasi')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="tinggi_genangan">Tinggi genangan <span>(cm)</span></label>
                <input id="tinggi_genangan" name="tinggi_genangan" type="number" min="1" max="1000" value="{{ old('tinggi_genangan') }}" required placeholder="Contoh: 45">
                @error('tinggi_genangan')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="tanggal_kejadian">Tanggal kejadian</label>
                <input id="tanggal_kejadian" name="tanggal_kejadian" type="date" max="{{ now()->toDateString() }}" value="{{ old('tanggal_kejadian', now()->toDateString()) }}" required>
                @error('tanggal_kejadian')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="form-actions">
            <p>Pastikan lokasi dan tinggi air sesuai kondisi di lapangan.</p>
            <button class="button button-primary" type="submit">Kirim laporan <span aria-hidden="true">→</span></button>
        </div>
    </form>
</section>
<section class="public-note"><span class="note-icon" aria-hidden="true">i</span><p>Warga dapat mengirim laporan tanpa akun. Data laporan hanya dapat dilihat oleh petugas yang sudah login.</p></section>
@endsection