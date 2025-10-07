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
    @livewireStyles
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
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: var(--text-color);
            min-height: 100vh;
            padding: 20px;
        }
        
        .vendor-registration-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo-placeholder {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--primary-color) 0%, #7fa19c 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            font-weight: bold;
            margin: 0 auto 15px auto;
            box-shadow: 0 4px 15px rgba(146, 182, 177, 0.3);
        }
        
        .page-title {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--primary-color);
            background: linear-gradient(135deg, var(--primary-color) 0%, #5a8d88 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .page-subtitle {
            text-align: center;
            color: var(--secondary-text);
            margin-bottom: 30px;
            font-size: 1.1rem;
        }
        
        /* Toggle Switch */
        .vendor-type-toggle {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            background: #f8f9fa;
            border-radius: 25px;
            padding: 5px;
            position: relative;
            border: 2px solid var(--primary-color);
        }
        
        .toggle-option {
            padding: 12px 25px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 2;
            text-align: center;
            flex: 1;
            font-weight: 500;
        }
        
        .toggle-option.active {
            color: white;
        }
        
        .toggle-slider {
            position: absolute;
            top: 5px;
            bottom: 5px;
            width: calc(50% - 10px);
            background: linear-gradient(135deg, var(--primary-color) 0%, #7fa19c 100%);
            border-radius: 20px;
            transition: all 0.3s ease;
            z-index: 1;
        }
        
        .toggle-slider.formal {
            left: 5px;
        }
        
        .toggle-slider.informal {
            left: calc(50% + 5px);
        }
        
        /* Progress Bar */
        .progress-container {
            margin-bottom: 30px;
        }
        
        .progress {
            height: 10px;
            border-radius: 5px;
            background: #e9ecef;
        }
        
        .progress-bar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #7fa19c 100%);
            border-radius: 5px;
            transition: width 0.5s ease;
        }
        
        /* Form Sections */
        .form-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            border: 1px solid var(--border-color);
        }
        
        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
        }
        
        .section-number {
            background: var(--primary-color);
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            margin-right: 12px;
        }
        
        /* Form Styles */
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
        
        /* Navigation Buttons */
        .form-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }
        
        /* Image Upload */
        .image-upload-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .image-upload-item {
            text-align: center;
        }
        
        .image-preview {
            width: 100%;
            height: 120px;
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            cursor: pointer;
            overflow: hidden;
        }
        
        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .image-preview-placeholder {
            color: var(--secondary-text);
            font-size: 2rem;
        }
        
        /* Responsive Design */
        @media (max-width: 576px) {
            .vendor-registration-container {
                padding: 15px;
                margin: 10px;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            .toggle-option {
                padding: 10px 15px;
                font-size: 0.9rem;
            }
            
            .form-section {
                padding: 20px 15px;
            }
            
            .form-navigation {
                flex-direction: column;
                gap: 10px;
            }
            
            .form-navigation .btn {
                width: 100%;
            }
            
            .image-upload-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-section {
            animation: fadeIn 0.5s ease;
        }

    </style>
</head>
<body>
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