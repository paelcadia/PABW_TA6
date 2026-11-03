@extends('layouts.app')

@section('title', 'Daftar Petugas | LaporBanjir')

@section('content')
<section class="auth-layout auth-register-layout">
    <div class="auth-aside"><p class="eyebrow">Akses internal <span class="eyebrow-divider">/</span> BPBD</p><h1>Kelola informasi kejadian dengan <span>akun petugas.</span></h1><p>Akun petugas diperlukan untuk membuka daftar laporan dan data profil.</p></div>
    <div class="auth-panel">
        <p class="eyebrow">Akun baru</p><h2>Daftar petugas</h2><p class="auth-caption">Lengkapi data untuk membuat akun akses petugas.</p>
        <form method="POST" action="{{ route('register.store') }}" class="stacked-form">
            @csrf
            <div class="field"><label for="name">Nama lengkap</label><input id="name" name="name" value="{{ old('name') }}" autocomplete="name" required autofocus maxlength="255">@error('name')<span class="field-error">{{ $message }}</span>@enderror</div>
            <div class="field"><label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>@error('email')<span class="field-error">{{ $message }}</span>@enderror</div>
            <div class="field"><label for="password">Kata sandi</label><input id="password" name="password" type="password" autocomplete="new-password" required>@error('password')<span class="field-error">{{ $message }}</span>@enderror</div>
            <div class="field"><label for="password_confirmation">Ulangi kata sandi</label><input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required></div>
            <button class="button button-primary button-full" type="submit">Buat akun <span aria-hidden="true">→</span></button>
        </form>
        <p class="auth-switch">Sudah terdaftar? <a href="{{ route('login') }}">Login</a></p>
    </div>
</section>
@endsection