<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Vendor - Work Spotter</title>
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
            background-color: #f8f9fa;
            color: var(--text-color);
            padding-bottom: 70px;
            padding-top: 56px;
        }
        
        /* Header Styles */
        .vendor-header {
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
        
        .vendor-header-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0 auto;
            text-align: center;
        }
        
        .vendor-header-icon {
            font-size: 1.5rem;
            width: 30px;
            color: white;
            text-decoration: none;
        }
        
        /* Content Styles */
        .vendor-content {
            padding: 16px;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 4px;
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: var(--secondary-text);
        }
        
        /* Section Cards */
        .section-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 16px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        
        .action-btn {
            background: white;
            border: 2px solid var(--primary-color);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .action-btn:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .action-icon {
            font-size: 1.5rem;
            margin-bottom: 8px;
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
        
        /* Recent Items */
        .recent-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .recent-item:last-child {
            border-bottom: none;
        }
        
        .recent-icon {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 12px;
        }
        
        .recent-content {
            flex: 1;
        }
        
        .recent-title {
            font-weight: 500;
            margin-bottom: 2px;
        }
        
        .recent-time {
            font-size: 0.8rem;
            color: var(--secondary-text);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="vendor-header">
        <a href="javascript:history.back()" class="vendor-header-icon">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="vendor-header-title">Dashboard Vendor</h1>
        <a href="" class="vendor-header-icon">
            <i class="bi bi-person"></i>
        </a>
    </header>

    <!-- Content Area -->
    <main class="vendor-content">
        @yield('content')
    </main>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="" class="nav-item active">
            <i class="bi bi-house nav-icon"></i>
            <span>Home</span>
        </a>
        <a href="" class="nav-item">
            <i class="bi bi-list-check nav-icon"></i>
            <span>Layanan</span>
        </a>
        <a href="" class="nav-item">
            <i class="bi bi-cart nav-icon"></i>
            <span>Pesanan</span>
        </a>
        <a href="" class="nav-item">
            <i class="bi bi-chat nav-icon"></i>
            <span>Chat</span>
        </a>
        <a href="" class="nav-item">
            <i class="bi bi-gear nav-icon"></i>
            <span>Settings</span>
        </a>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>