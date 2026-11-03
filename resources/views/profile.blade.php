@extends('layouts.app')

@section('title', 'Profil Petugas | LaporBanjir')

@section('content')
<section class="page-heading profile-heading">
    <div><p class="eyebrow">Ruang petugas <span class="eyebrow-divider">/</span> Akun aktif</p><h1>Profil petugas</h1><p class="heading-copy">Informasi akun yang sedang digunakan untuk mengakses data laporan.</p></div>
</section>
<section class="profile-sheet">
    <div class="profile-avatar" aria-hidden="true">{{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}</div>
    <div class="profile-details">
        <div><span>Nama petugas</span><strong>{{ Auth::user()->name }}</strong></div>
        <div><span>Email</span><strong>{{ Auth::user()->email }}</strong></div>
        <div><span>Akun dibuat</span><strong>{{ Auth::user()->created_at->translatedFormat('d F Y, H:i') }}</strong><small>{{ Auth::user()->created_at->diffForHumans() }}</small></div>
    </div>
</section>
@endsection