<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Perwalian') - SIAKAD Perwalian</title>
    
    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        body { background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background: #1e293b; }
        .sidebar a { color: #cbd5e1; text-decoration: none; display: block; padding: .6rem 1rem; border-radius: .375rem; }
        .sidebar a.active, .sidebar a:hover { background: #334155; color: #fff; }
        .navbar-brand { font-weight: 600; }
        .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar p-3" style="width: 240px;">
        <div class="text-white fs-5 fw-bold mb-4"><i class="bi bi-mortarboard-fill"></i> Perwalian</div>
        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="{{ route('admin.dosen.index') }}" class="{{ request()->routeIs('admin.dosen.*') ? 'active' : '' }}"><i class="bi bi-person-badge"></i> Dosen Wali</a>
                <a href="{{ route('admin.mahasiswa.index') }}" class="{{ request()->routeIs('admin.mahasiswa.*') ? 'active' : '' }}"><i class="bi bi-people"></i> Mahasiswa</a>
                <a href="{{ route('admin.import.index') }}" class="{{ request()->routeIs('admin.import.*') ? 'active' : '' }}"><i class="bi bi-upload"></i> Import Data</a>
                <a href="{{ route('admin.perwalian.index') }}" class="{{ request()->routeIs('admin.perwalian.*') ? 'active' : '' }}"><i class="bi bi-journal-text"></i> Rekap Perwalian</a>
            @elseif(auth()->user()->role === 'dosen')
                <a href="{{ route('dosen.dashboard') }}" class="{{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="{{ route('dosen.perwalian.index') }}" class="{{ request()->routeIs('dosen.perwalian.*') ? 'active' : '' }}"><i class="bi bi-journal-text"></i> Histori Perwalian</a>
            @elseif(auth()->user()->role === 'mahasiswa')
                <a href="{{ route('mahasiswa.dashboard') }}" class="{{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="{{ route('mahasiswa.perwalian.index') }}" class="{{ request()->routeIs('mahasiswa.perwalian.*') ? 'active' : '' }}"><i class="bi bi-journal-text"></i> Perwalian Saya</a>
            @endif
        @endauth
    </nav>

    <!-- Main Content -->
    <div class="flex-grow-1">
        <nav class="navbar navbar-expand navbar-light bg-white shadow-sm px-4">
            <span class="navbar-brand">@yield('page-title', 'Dashboard')</span>
            <div class="ms-auto d-flex align-items-center gap-3">
                @auth
                    <span class="text-muted small">{{ auth()->user()->name }} <span class="badge bg-secondary text-uppercase">{{ auth()->user()->role }}</span></span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">Keluar</button>
                    </form>
                @endauth
            </div>
        </nav>

        <main class="p-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<!-- JS Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Tempat Script Tambahan dari View Child -->
@stack('scripts')
</body>
</html>