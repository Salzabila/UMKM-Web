@extends('layouts.main')

@section('container')
    <style>
        body {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fecfef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Arial', sans-serif;
        }
        .error-container {
            text-align: center;
            color: #333;
            animation: slideUp 1s ease-out;
        }
        .error-icon {
            font-size: 8rem;
            color: #e74c3c;
            animation: pulse 2s infinite;
        }
        .error-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 20px 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .error-subtitle {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.8;
        }
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .btn-custom {
            margin: 0 10px;
            padding: 12px 25px;
            font-size: 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        .btn-dashboard {
            background: linear-gradient(45deg, #3498db, #2980b9);
            border: none;
        }
        .btn-admin {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            border: none;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>

    <div class="error-container">
        <i class="bi bi-person-x error-icon"></i>
        <h1 class="error-title">MAAF FITUR INI HANYA UNTUK ADMIN !!!</h1>
        <p class="error-subtitle">Anda tidak memiliki akses untuk halaman ini</p>
        <div>
            <a href="/dashboard" class="btn btn-dashboard btn-custom text-white">
                <i class="bi bi-house"></i> Kembali ke Dashboard
            </a>
            <a href="/loginadmin" class="btn btn-admin btn-custom text-white">
                <i class="bi bi-person-gear"></i> Login Admin
            </a>
        </div>
    </div>
@endsection
