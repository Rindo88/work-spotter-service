<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang | Work Spotter</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Unified CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body class="welcome-page">
    <div class="welcome-container">
        <img src="{{ asset('assets/images/undraw_coffee-run.svg') }}" alt="Work Spotter Illustration">
        
        <h1 class="welcome-title">Selamat Datang di<br>Work Spotter</h1>
        <p class="welcome-description">
            Temukan dan kelola lokasi pedagang dengan mudah. 
            Akses informasi lokasi, jadwal, dan aktivitas hanya dalam satu aplikasi.
        </p>

        <a href="{{ route('login') }}" class="welcome-btn welcome-btn-login">Login</a>
        <a href="{{ route('register') }}" class="welcome-btn welcome-btn-register">Daftar</a>
    </div>
</body>
</html>
