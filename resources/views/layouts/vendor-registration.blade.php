<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Sebagai Pedagang - Work Spotter</title>
    
    <!-- Google Fonts - Work Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Unified CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @livewireStyles
</head>
<body class="vendor-registration">
    <div class="vendor-registration-container">
        <!-- Logo & Header -->
        <div class="logo-container">
            <div class="logo-placeholder">WS</div>
            <h1 class="page-title">Join Pedagang</h1>
            <p class="page-subtitle">Daftarkan usaha Anda dan mulai berkembang bersama Work Spotter</p>
        </div>
        
        {{ $slot }}
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
    @stack('scripts')
</body>
</html>