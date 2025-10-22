<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Work Spotter' }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fff;
        }

        .auth-header {
            background-color: #92B6B1;
            padding: 25px 0;
            text-align: center;
            border-bottom-left-radius: 25px;
            border-bottom-right-radius: 25px;
        }

        .auth-header img {
            max-height: 50px;
            height: auto;
        }

        .auth-container {
            max-width: 420px;
            margin: 40px auto;
            padding: 0 20px;
        }
    </style>
</head>

<body>
    <div class="auth-header d-flex align-items-center justify-content-center text-center">
        <img src="{{ asset('assets/images/logo-workspotter.png') }}" alt="Work Spotter Logo">
    </div>

    <div class="auth-container">
        {{ $slot }}
    </div>

    {{-- Bootstrap JS (opsional) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
