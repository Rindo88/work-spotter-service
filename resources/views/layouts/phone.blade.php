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
    <style>
        :root {
            --primary-color: #92B6B1;
            --background-color: #FFFFFF;
            --text-color: #333333;
            --secondary-text: #666666;
            --border-color: #E0E0E0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            padding-bottom: 70px; /* Space for bottom navbar */
            padding-top: 56px; /* Space for top header */
        }
        
        /* Header Styles */
        .header {
            background-color: var(--primary-color);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 56px;
            display: flex;
            align-items: center;
            padding: 0 16px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .header-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0 auto;
            text-align: center;
        }
        
        .header-icon {
            font-size: 1.5rem;
            width: 30px;
            color: white;
            text-decoration: none;
        }
        
        /* Content Styles */
        .content {
            padding: 16px;
            min-height: calc(100vh - 126px);
        }
        
        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: white;
            display: flex;
            justify-content: space-around;
            padding: 8px 0;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        
        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--secondary-text);
            font-size: 0.75rem;
        }
        
        .nav-item.active {
            color: var(--primary-color);
        }
        
        .nav-icon {
            font-size: 1.25rem;
            margin-bottom: 4px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <a href="javascript:history.back()" class="header-icon">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="header-title">@yield('header-title', 'Work Spotter')</h1>
        <a href="#" class="header-icon">
            <i class="bi bi-three-dots-vertical"></i>
        </a>
    </header>

    <!-- Content Area -->
    <main class="content">
        @yield('content')
    </main>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="{{ route('home') }}" class="nav-item {{ Request::routeIs('home') ? 'active' : '' }}">
            <i class="bi bi-house nav-icon"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('home') }}" class="nav-item {{ Request::routeIs('home') ? 'active' : '' }}">
            <i class="bi bi-search nav-icon"></i>
            <span>Cari</span>
        </a>
        <a href="{{ route('home') }}" class="nav-item {{ Request::routeIs('home') ? 'active' : '' }}">
            <i class="bi bi-geo-alt nav-icon"></i>
            <span>Peta</span>
        </a>
        <a href="{{ route('home') }}" class="nav-item {{ Request::routeIs('home') ? 'active' : '' }}">
            <i class="bi bi-chat-dots nav-icon"></i>
            <span>Chat</span>
        </a>
        <a href="{{ route('profile') }}" class="nav-item {{ Request::routeIs('profile') ? 'active' : '' }}">
            <i class="bi bi-person nav-icon"></i>
            <span>Profil</span>
        </a>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Simple script to handle navigation active states
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                document.querySelectorAll('.nav-item').forEach(nav => {
                    nav.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>