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
            padding: 20px;
        }
        
        .vendor-registration-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo-placeholder {
            width: 120px;
            height: 120px;
            background-color: var(--primary-color);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto 15px auto;
        }
        
        .page-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 30px;
            color: var(--primary-color);
        }
        
        .form-section {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .section-title {
            text-align: center;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--primary-color);
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-control {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 12px 15px;
            font-size: 1rem;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(146, 182, 177, 0.25);
        }
        
        .form-select {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 12px 15px;
            font-size: 1rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 12px 20px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: #7fa19c;
            border-color: #7fa19c;
        }
        
        .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
            border-radius: 8px;
            padding: 10px 15px;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .input-hint {
            font-size: 0.85rem;
            color: var(--secondary-text);
            margin-top: 5px;
        }
        
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
        
        .photo-upload-container {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .profile-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-color);
            margin: 0 auto 15px auto;
        }
        
        .upload-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .upload-btn:hover {
            background-color: #7fa19c;
        }
        
        .coordinate-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="vendor-registration-container">
        <!-- Logo -->
        <div class="logo-container">
            <div class="logo-placeholder">WS</div>
            <h1 class="page-title">Join Pedagang</h1>
        </div>
        
        <!-- Form Content -->
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>