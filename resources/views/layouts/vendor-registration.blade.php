<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Sebagai Pedagang - Work Spotter</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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