<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BUMDes Kampar Sejahtera</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }

        body {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 50%, #0d6efd 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .login-logo {
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, #198754, #0d6efd);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            margin: 0 auto 20px;
        }

        .login-title {
            font-size: 22px;
            font-weight: 700;
            color: #1a202c;
            text-align: center;
            margin-bottom: 5px;
        }

        .login-subtitle {
            font-size: 13px;
            color: #718096;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: 11px 14px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13,110,253,0.1);
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: #4a5568;
        }

        .btn-login {
            background: linear-gradient(135deg, #0d6efd, #198754);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 14px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13,110,253,0.3);
            color: white;
        }

        .input-group-text {
            border-radius: 0 10px 10px 0;
            border: 1.5px solid #e2e8f0;
            border-left: none;
            background: #f8fafc;
            cursor: pointer;
        }

        .input-group .form-control {
            border-radius: 10px 0 0 10px;
            border-right: none;
        }

        .footer-text {
            text-align: center;
            font-size: 12px;
            color: #a0aec0;
            margin-top: 25px;
        }

        .decorative-circle {
            position: fixed;
            border-radius: 50%;
            opacity: 0.1;
            background: white;
        }
    </style>
</head>
<body>
    <!-- Decorative circles -->
    <div class="decorative-circle" style="width:300px;height:300px;top:-100px;right:-100px;"></div>
    <div class="decorative-circle" style="width:200px;height:200px;bottom:-50px;left:-50px;"></div>

    <div class="login-card">
        <!-- Logo -->
        <div class="login-logo">
            <i class="bi bi-bank"></i>
        </div>

        <h1 class="login-title">BUMDes Kampar Sejahtera</h1>
        <p class="login-subtitle">Sistem Pengelolaan Dana Desa</p>

        <!-- Alert -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger mb-3">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <!-- Form -->
        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text" style="border-radius:10px 0 0 10px;border:1.5px solid #e2e8f0;border-right:none;background:#f8fafc;">
                        <i class="bi bi-person text-muted"></i>
                    </span>
                    <input
                        type="text"
                        name="username"
                        class="form-control @error('username') is-invalid @enderror"
                        placeholder="Masukkan username"
                        value="{{ old('username') }}"
                        style="border-radius:0 10px 10px 0;border-left:none;"
                        required
                        autofocus
                    >
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text" style="border-radius:10px 0 0 10px;border:1.5px solid #e2e8f0;border-right:none;background:#f8fafc;">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input
                        type="password"
                        name="password"
                        id="passwordInput"
                        class="form-control"
                        placeholder="Masukkan password"
                        style="border-radius:0 0 0 0;border-left:none;border-right:none;"
                        required
                    >
                    <span class="input-group-text" onclick="togglePassword()">
                        <i class="bi bi-eye" id="eyeIcon" style="color:#718096;"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
            </button>
        </form>

        <div class="footer-text">
            &copy; {{ date('Y') }} BUMDes Kampar Sejahtera. All rights reserved.
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon  = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>
</body>
</html>