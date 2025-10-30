<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Admin & Manajer - GERAI UMKM MART</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }
        .login-page {
            width: 100%;
            max-width: 400px;
            padding: 1rem;
        }
        .card {
            border: 1px solid #e6e7e9;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);
            border-radius: 8px;
        }
        .card-body {
            padding: 2rem;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header .logo {
            display: inline-block;
            width: 48px;
            height: 48px;
            margin-bottom: 1rem;
            color: #28a745;
        }
        .login-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .login-header p {
            color: #6c757d;
        }
        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #e6e7e9;
            padding: 0.75rem 1rem;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(179, 158, 158, 0.25);
        }
        .password-container {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 0;
            font-size: 1rem;
            transition: color 0.2s ease;
        }
        .password-toggle:hover {
            color: #28a745;
        }
        .password-container .form-control {
            padding-right: 40px;
        }
        .btn-danger {
            background-color: #28a745;
            border-color: #28a745;
            border-radius: 8px;
            font-weight: 600;
            padding: 0.75rem;
        }
        .btn-danger:hover {
            background-color: #28a745;
            border-color: #28a745;
        }
        .captcha-container {
            background: #f8fafc;
            border: 1px solid #e6e7e9;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            margin-bottom: 1.5rem;
            position: relative;
        }
        .captcha-code {
            font-family: 'Courier New', monospace;
            font-size: 1.5rem;
            font-weight: bold;
            color: #1d273b;
            text-decoration: line-through;
            letter-spacing: 3px;
        }
        .captcha-refresh {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: none;
            border: none;
            color: #6c757d;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.3s ease, color 0.2s ease;
        }
        .captcha-refresh:hover {
            color: #1d273b;
            transform: rotate(180deg);
        }
        .footer-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .footer-link a {
            color: #6c757d;
            text-decoration: none;
            font-size: 0.875rem;
        }
        .footer-link a:hover {
            text-decoration: underline;
        }
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="login-header">
            <svg xmlns="http://www.w3.org/2000/svg" class="logo" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
            <h1>Login Admin & Manajer</h1>
            <p>Akses panel kontrol utama GERAI UMKM MART </p>
        </div>

        <div class="card">
            <div class="card-body">
                @if(session()->has('loginError'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        {{ session('loginError') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="/loginadmin" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                               id="username" name="username" placeholder="Masukkan username" value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="password-container">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" placeholder="Masukkan password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="bi bi-eye" id="password-icon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="captcha-container">
                        <button type="button" class="captcha-refresh" onclick="refreshCaptcha()">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                        <div class="captcha-code" id="captcha-display"></div>
                    </div>

                    <div class="mb-3">
                        <label for="captcha_code" class="form-label">Kode Verifikasi</label>
                        <input type="text" class="form-control @error('captcha_code') is-invalid @enderror"
                               id="captcha_code" name="captcha_code" placeholder="Masukkan kode di atas" required>
                        @error('captcha_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-danger w-100">
                        Login Admin & Manajer
                    </button>
                </form>
            </div>
        </div>

        <div class="footer-link">
            <a href="/"><i class="bi bi-arrow-left"></i> Kembali ke Login Kasir</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const passwordIcon = document.getElementById(inputId + '-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'bi bi-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'bi bi-eye';
            }
        }

        let currentCaptcha = '';

        function generateCaptcha() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let captcha = '';
            for (let i = 0; i < 6; i++) {
                captcha += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return captcha;
        }

        function refreshCaptcha() {
            currentCaptcha = generateCaptcha();
            document.getElementById('captcha-display').textContent = currentCaptcha;

            fetch('/store-captcha', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ captcha: currentCaptcha })
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            refreshCaptcha();
        });
    </script>
</body>
</html>
