<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LaporBanjir | BPBD Kabupaten Bandung')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css'])
    @endif
</head>
<body>
    <div class="site-shell">
        <header class="topbar">
            <a class="brand" href="{{ route('lapor.form') }}" aria-label="LaporBanjir, halaman utama">
                <span class="brand-mark" aria-hidden="true">LB</span>
                <span class="brand-copy"><strong>LaporBanjir</strong><small>BPBD Kabupaten Bandung</small></span>
            </a>
            <nav class="main-nav" aria-label="Navigasi utama">
                <a href="{{ route('lapor.form') }}" @class(['nav-link', 'is-active' => request()->routeIs('lapor.form')])>Kirim Laporan</a>
                @auth
                    <a href="{{ route('laporan.index') }}" @class(['nav-link', 'is-active' => request()->routeIs('laporan.*')])>Daftar Laporan</a>
                    <a href="{{ route('profile') }}" @class(['nav-link', 'is-active' => request()->routeIs('profile')])>Profil Petugas</a>
                    <form method="POST" action="{{ route('logout') }}" class="nav-logout">
                        @csrf
                        <button class="nav-link nav-button" type="submit">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" @class(['nav-link', 'is-active' => request()->routeIs('login')])>Login</a>
                    <a class="nav-register" href="{{ route('register') }}">Register</a>
                @endauth
            </nav>
        </header>

        <main class="page-content">
            @if (session('success'))
                <div class="notice notice-success" role="status">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="notice notice-error" role="alert">
                    <strong>Periksa kembali data yang dimasukkan.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>

        <footer class="site-footer">
            <span>Pusat informasi laporan banjir</span>
            <span>Kabupaten Bandung <span class="footer-dot">/</span> Layanan warga</span>
        </footer>
    </div>
</body>
</html>