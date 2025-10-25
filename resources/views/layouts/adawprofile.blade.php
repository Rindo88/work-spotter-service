{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Work Spotter')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Unified CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
    @livewireStyles
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark gradient-bg">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="bi bi-briefcase me-2"></i>Work Spotter
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Beranda</a>
                    </li>
                    @if(Auth::user() && Auth::user()->vendor)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vendor.dashboard') }}">Dashboard Vendor</a>
                    </li>
                    @endif
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.settings') }}"><i class="bi bi-gear me-2"></i>Pengaturan</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.security') }}"><i class="bi bi-shield-lock me-2"></i>Keamanan</a></li>
                            @if(Auth::user()->vendor)
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('vendor.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard Vendor</a></li>
                            <li><a class="dropdown-item" href="{{ route('vendor.profile') }}"><i class="bi bi-shop me-2"></i>Profil Vendor</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar hanya untuk vendor -->
            @if(Request::is('vendor/*') && Auth::user() && Auth::user()->vendor)
            <div class="col-lg-2 col-md-3 sidebar p-0">
                <div class="p-3">
                    <h6 class="text-white fw-bold mb-3">VENDOR PANEL</h6>
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('vendor/dashboard') ? 'active' : '' }}" 
                               href="{{ route('vendor.dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('vendor/profile') ? 'active' : '' }}" 
                               href="{{ route('vendor.profile') }}">
                                <i class="bi bi-shop me-2"></i>Profil Vendor
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('vendor/services') ? 'active' : '' }}" 
                               href="{{ route('vendor.services') }}">
                                <i class="bi bi-list-task me-2"></i>Layanan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('vendor/schedule') ? 'active' : '' }}" 
                               href="{{ route('vendor.schedule') }}">
                                <i class="bi bi-clock me-2"></i>Jadwal
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            @endif

            <!-- Main Content -->
            <div class="{{ Request::is('vendor/*') && Auth::user() && Auth::user()->vendor ? 'col-lg-10 col-md-9' : 'col-12' }}">
                <main class="py-4">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
</body>
</html>