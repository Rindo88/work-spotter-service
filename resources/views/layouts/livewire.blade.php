<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Spotter - @yield('title', 'Chat')</title>

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

        .header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 56px;
        }

        .header.hidden {
            transform: translateY(-100%);
            opacity: 0;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            border-top: 1px solid #dee2e6;
        }

        .nav-item.active {
            color: var(--primary-color) !important;
        }
    </style>

    @livewireStyles
</head>

<body>
    <!-- Header -->
    <header class="header" id="main-header">
        <div class="d-flex align-items-center h-100 px-3">
            @if (!request()->is('/'))
                <a href="javascript:history.back()" class="text-white text-decoration-none">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
            @else
                <div style="width: 24px;"></div>
            @endif

            <!-- Logo di tengah -->
            <div class="flex-grow-1 text-center">
                <img src="{{ asset('assets/images/logo-workspotter.png') }}" alt="Work Spotter Logo" class="img-fluid"
                    style="max-height: 48px; height: auto;">
            </div>

            @if (!request()->is('/'))
                <a href="#" class="text-white text-decoration-none">
                    <i class="bi bi-three-dots-vertical fs-5"></i>
                </a>
            @else
                <div style="width: 24px;"></div>
            @endif
        </div>
    </header>

    <!-- Content Area - GUNAKAN $slot untuk Livewire -->
    <main class="content">
        {{ $slot }}
    </main>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <div class="d-flex justify-content-around py-2">
            <a href="{{ route('home') }}" 
                class="nav-item text-decoration-none d-flex flex-column align-items-center {{ request()->routeIs('home') ? 'active text-primary' : 'text-muted' }}">
                <i class="bi bi-house fs-5"></i>
                <small>Home</small>
            </a>
            <a href="{{ route('home') }}" 
                class="nav-item text-decoration-none d-flex flex-column align-items-center {{ request()->routeIs('search') ? 'active text-primary' : 'text-muted' }}">
                <i class="bi bi-search fs-5"></i>
                <small>Cari</small>
            </a>
            <a href="{{ route('checkin') }}"
                class="nav-item text-decoration-none d-flex flex-column align-items-center {{ request()->routeIs('map') ? 'active text-primary' : 'text-muted' }}">
                <i class="bi bi-geo-alt fs-5"></i>
                <small>Peta</small>
            </a>
           
            <a href="{{ route('chat.index') }}"
                class="nav-item text-decoration-none d-flex flex-column align-items-center {{ request()->routeIs('chat.*') ? 'active text-primary' : 'text-muted' }}">
                <i class="bi bi-chat-dots fs-5"></i>
                <small>Chat</small>
            </a>
            <a href="{{ route('profile') }}"
                class="nav-item text-decoration-none d-flex flex-column align-items-center {{ request()->routeIs('profile') ? 'active text-primary' : 'text-muted' }}">
                <i class="bi bi-person fs-5"></i>
                <small>Profil</small>
            </a>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts

    <script>
        // Navigation active states
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-item').forEach(item => {
                if (item.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                }
            });
        });

        // Header hide/show on scroll (only for home)
        @if(request()->is('/'))
            document.addEventListener('DOMContentLoaded', function() {
                const header = document.getElementById('main-header');
                let lastScrollTop = 0;

                window.addEventListener('scroll', function() {
                    const currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;

                    if (currentScrollTop > lastScrollTop && currentScrollTop > 60) {
                        header.classList.add('hidden');
                    } else if (currentScrollTop < lastScrollTop) {
                        header.classList.remove('hidden');
                    }

                    lastScrollTop = currentScrollTop;
                });
            });
        @endif
    </script>

    @stack('scripts')
</body>
</html>