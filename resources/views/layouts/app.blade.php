{{-- resources/views/layouts/profile.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Work Spotter - Profile')</title>

    <!-- Google Fonts - Work Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    @livewireStyles
    @stack('styles')
</head>

<body class="layout-app with-header">
    <!-- Header Baru -->
    <header class="header layout-header">
        <div class="d-flex align-items-center w-100">
            @if (!request()->routeIs('home'))
                <a href="javascript:history.back()" class="me-3">
                    <i class="bx bx-arrow-back fs-5 mt-2"></i>
                </a>
            @endif
            <h5 class="fw-bold mb-0">@yield('header-title', 'Work Spotter')</h5>
        </div>
    </header>

    <!-- Content Area -->
    <main class="content mt-3 px-3">
        @yield('content')
    </main>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav layout-nav">
        <div class="d-flex justify-content-around py-2">
            <a href="{{ route('home') }}" x-navigate
                class="nav-item text-decoration-none d-flex flex-column align-items-center {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="bx bx-home fs-5"></i>
                <small>Home</small>
            </a>
            <a href="{{ route('home') }}" x-navigate
                class="nav-item text-decoration-none d-flex flex-column align-items-center {{ request()->routeIs('search') ? 'active' : '' }}">
                <i class="bx bx-search fs-5"></i>
                <small>Cari</small>
            </a>
            <a href="@if (auth()->user()->isVendor()) {{ route('checkin') }} @else {{ route('user.map') }} @endif"
                class="nav-item text-decoration-none d-flex flex-column align-items-center {{ request()->routeIs('map') ? 'active' : '' }}">
                <i class="bx bx-map-pin fs-5"></i>
                <small>Peta</small>
            </a>
            <a href="{{ route('chat.index') }}"
                class="nav-item text-decoration-none d-flex flex-column align-items-center {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                <i class="bx bx-chat fs-5"></i>
                <small>Chat</small>
            </a>
            <a href="{{ route('profile') }}" x-navigate
                class="nav-item text-decoration-none d-flex flex-column align-items-center {{ request()->routeIs('profile') ? 'active' : '' }}">
                <i class="bx bx-user fs-5"></i>
                <small>Profil</small>
            </a>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts

    <script>
        // Highlight active nav
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-item').forEach(item => {
                if (item.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
