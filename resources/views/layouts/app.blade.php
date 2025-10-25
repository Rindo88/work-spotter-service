{{-- resources/views/layouts/profile.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Work Spotter - Profile')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #92B6B1;
            --secondary-color: #6D8B87;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding-bottom: 70px;
            padding-top: 56px;
        }

        /* Header baru */
        .header {
            background-color: white;
            border-bottom: 1px solid #dee2e6;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 56px;
            display: flex;
            align-items: center;
            padding: 0 1rem;
        }

        .header h5 {
            font-weight: 600;
            margin: 0;
        }

        .header a {
            color: #212529;
            text-decoration: none;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            border-top: none;
        }

        .bottom-nav .nav-item {
            color: rgba(255, 255, 255, 0.8);
        }

        .bottom-nav .nav-item.active {
            color: #fff !important;
            font-weight: 600;
        }

        .bottom-nav .nav-item:hover {
            color: #fff !important;
        }

        /* Buttons and palette */
        .btn-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            color: #fff !important;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: var(--secondary-color) !important;
            border-color: var(--secondary-color) !important;
        }

        .btn-outline-primary {
            color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:focus {
            background-color: var(--primary-color) !important;
            color: #fff !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .border-primary {
            border-color: var(--primary-color) !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 0.25rem rgba(146, 182, 177, 0.25) !important;
        }
    </style>

    @livewireStyles
    @stack('styles')
</head>

<body>
    <!-- Header Baru -->
    <header class="header">
        <div class="d-flex align-items-center w-100">
<<<<<<< HEAD
            <a href="javascript:history.back()" class="me-3">
                <i class="bx bx-arrow-back fs-5"></i>
            </a>
=======
            @if (!request()->routeIs('home'))
                <a href="javascript:history.back()" class="me-3">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
            @endif
>>>>>>> 54b3068ea137b2c286cd7f6181034060903e3959
            <h5 class="fw-bold mb-0">@yield('header-title', 'Work Spotter')</h5>
        </div>
    </header>

    <!-- Content Area -->
    <main class="content mt-3 px-3">
        @yield('content')
    </main>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
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
