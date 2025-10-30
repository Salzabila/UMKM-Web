<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - KasirKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Arial', sans-serif;
        }
        .error-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            animation: bounce 1s ease-out;
        }
        @keyframes bounce {
            0% { transform: translateY(-100px); opacity: 0; }
            50% { transform: translateY(20px); }
            100% { transform: translateY(0); opacity: 1; }
        }
        .error-icon {
            font-size: 5rem;
            color: #ff6b6b;
            margin-bottom: 20px;
            animation: shake 0.5s ease-in-out infinite alternate;
        }
        @keyframes shake {
            0% { transform: rotate(-5deg); }
            100% { transform: rotate(5deg); }
        }
        .error-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .error-message {
            font-size: 1.2rem;
            color: #7f8c8d;
            margin-bottom: 30px;
        }
        .btn-back {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            color: white;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(52, 152, 219, 0.3);
            color: white;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="bi bi-shield-exclamation"></i>
        </div>
        <h1 class="error-title">Mohon Maaf Anda Tidak Bisa Mengakses Halaman Ini :)</h1>
        <p class="error-message">
            Silakan login menggunakan akun yang sesuai kebutuhan anda untuk mengakses sistem.
        </p>
        <a href="/" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali ke Login
        </a>
    </div>
</body>
</html>
