<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - GERAI UMKM MART</title>
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
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
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
            color: #206bc4;
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
            border-color: #206bc4;
            box-shadow: 0 0 0 0.2rem rgba(32, 107, 196, 0.25);
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
            color: #206bc4;
        }

        .password-container .form-control {
            padding-right: 40px;
        }

        .btn-primary {
            background-color: #206bc4;
            border-color: #206bc4;
            border-radius: 8px;
            font-weight: 600;
            padding: 0.75rem;
        }

        .btn-primary:hover {
            background-color: #1c5ba1;
            border-color: #1c5ba1;
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

        .captcha-question {
            font-family: 'Courier New', monospace;
            font-size: 1.5rem;
            font-weight: bold;
            color: #1d273b;
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
            <svg xmlns="http://www.w3.org/2000/svg" class="logo" width="24" height="24" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M3 21l18 0" />
                <path d="M5 21v-14l8 -4v18" />
                <path d="M19 21v-10l-6 -4" />
                <path d="M9 9l0 .01" />
                <path d="M9 12l0 .01" />
                <path d="M9 15l0 .01" />
                <path d="M9 18l0 .01" />
            </svg>
            <h1>Login Sistem</h1>
            <p>Selamat datang kembali di GERAI UMKM MART</p>
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

                @if(session()->has('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle-fill"></i>
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="/login" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                            name="username" placeholder="Masukkan username" value="{{ old('username') }}" required>
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
                        <button type="button" class="captcha-refresh" onclick="refreshMathCaptcha()">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                        <div class="captcha-question" id="math-question"></div>
                    </div>

                    <div class="mb-3">
                        <label for="captcha_answer" class="form-label">Jawaban Verifikasi</label>
                        <input type="number" class="form-control @error('captcha_answer') is-invalid @enderror"
                            id="captcha_answer" name="captcha_answer" placeholder="Masukkan jawaban" required>
                        @error('captcha_answer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1"
                            style="width:14px; height:14px;">
                        <label class="form-check-label" for="remember" style="font-size: 14px;">
                            <i class="bi bi-clock-history"></i> Ingat Saya
                        </label>
                    </div>


                    <button type="submit" class="btn btn-primary w-100">
                        Login Sistem
                    </button>
                </form>
            </div>
        </div>

        <div class="footer-link">
            <small class="text-muted">
                <i class="bi bi-shield-check"></i>
                Sistem login terpadu untuk Kasir, Admin & Manajer
            </small>
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

        let currentAnswer = 0;

        function generateMathCaptcha() {
            const operations = ['+', '-', '×'];
            const operation = operations[Math.floor(Math.random() * operations.length)];
            let num1, num2, answer;

            if (operation === '+') {
                num1 = Math.floor(Math.random() * 20) + 1;
                num2 = Math.floor(Math.random() * 20) + 1;
                answer = num1 + num2;
            } else if (operation === '-') {
                num1 = Math.floor(Math.random() * 20) + 10;
                num2 = Math.floor(Math.random() * 10) + 1;
                answer = num1 - num2;
            } else {
                num1 = Math.floor(Math.random() * 10) + 1;
                num2 = Math.floor(Math.random() * 10) + 1;
                answer = num1 * num2;
            }

            return { question: `${num1} ${operation} ${num2} = ?`, answer: answer };
        }

        function refreshMathCaptcha() {
            const captcha = generateMathCaptcha();
            currentAnswer = captcha.answer;
            document.getElementById('math-question').textContent = captcha.question;

            fetch('/store-math-captcha', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ answer: currentAnswer })
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            refreshMathCaptcha();
        });
    </script>
</body>

</html>