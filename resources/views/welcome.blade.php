<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang | Work Spotter</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            width: 90%;
            max-width: 400px;
        }
        .container img {
            width: 80%;
            max-width: 280px;
            margin-bottom: 30px;
        }
        h1 {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
        }
        p {
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 40px;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            font-size: 1rem;
            border: none;
            border-radius: 12px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: 0.3s ease;
        }
        .btn-login {
            background-color: #92B6B1;
            color: white;
        }
        .btn-login:hover {
            background-color: #7fa49e;
        }
        .btn-register {
            background-color: #E8E8E8;
            color: #333;
        }
        .btn-register:hover {
            background-color: #d9d9d9;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('assets/images/undraw_coffee-run.svg') }}" alt="Work Spotter Illustration">
        
        <h1>Selamat Datang di<br>Work Spotter</h1>
        <p>
            Temukan dan kelola lokasi pedagang dengan mudah. 
            Akses informasi lokasi, jadwal, dan aktivitas hanya dalam satu aplikasi.
        </p>

        <button class="btn btn-login" onclick="window.location='{{ route('login') }}'">Login</button>
        <button class="btn btn-register" onclick="window.location='{{ route('register') }}'">Daftar</button>
    </div>
</body>
</html>
