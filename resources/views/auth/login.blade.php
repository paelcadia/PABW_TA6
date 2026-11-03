@extends('layouts.app')

@section('title', 'Login Petugas | LaporBanjir')

@section('content')
<section class="auth-layout">
    <div class="auth-aside"><p class="eyebrow">Akses internal <span class="eyebrow-divider">/</span> BPBD</p><h1>Informasi yang tepat membantu <span>respons yang cepat.</span></h1><p>Masuk untuk memantau laporan warga dan melihat data kejadian.</p></div>
    <div class="auth-panel">
        <p class="eyebrow">Petugas Kabupaten Bandung</p><h2>Login</h2><p class="auth-caption">Gunakan email dan kata sandi akun petugas.</p>
        <form method="POST" action="{{ route('login.store') }}" class="stacked-form">
            @csrf
            <div class="field"><label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>@error('email')<span class="field-error">{{ $message }}</span>@enderror</div>
            <div class="field"><label for="password">Kata sandi</label><input id="password" name="password" type="password" autocomplete="current-password" required>@error('password')<span class="field-error">{{ $message }}</span>@enderror</div>
            <label class="check-field"><input type="checkbox" name="remember" value="1" @checked(old('remember'))><span>Ingat saya di perangkat ini</span></label>
            <button class="button button-primary button-full" type="submit">Masuk ke akun <span aria-hidden="true">→</span></button>
        </form>
        <p class="auth-switch">Belum memiliki akun petugas? <a href="{{ route('register') }}">Daftar akun</a></p>
    </div>
</section>
@endsection