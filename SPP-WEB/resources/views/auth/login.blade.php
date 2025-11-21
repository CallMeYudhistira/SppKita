<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SPP</title>

    <link href="{{ asset('bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('boxicons/css/boxicons.min.css') }}">

    <style>
        @font-face {
            font-family: 'Elms Sans';
            src: url('{{ asset('font/static/ElmsSans-Regular.ttf') }}') format('truetype');
        }

        body {
            background: linear-gradient(135deg, #f3f6fd 0%, #f0f3fa 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Elms Sans', sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 16px;
            padding: 35px;
            background: #ffffff;
            border: 1px solid #e5e9f2;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .login-title {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #25396f;
        }

        .login-sub {
            color: #6c757d;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .form-control {
            border-radius: 10px;
            height: 48px;
        }

        /* Toggle button yang elegan */
        .toggle-btn {
            border: none;
            background: transparent;
            font-size: 20px;
            color: #6c757d;
            padding: 0 12px;
            cursor: pointer;
        }

        .toggle-btn:hover {
            color: #3752f5;
        }

        .btn-login {
            height: 48px;
            border-radius: 10px;
            font-weight: 600;
            background: #3752f5;
            border: none;
        }

        .btn-login:hover {
            background: #2c42d3;
        }
    </style>
</head>

<body>

    <div class="login-card">

        <!-- Logo berada di tengah -->
        <img src="{{ asset('image/logo_ti.png') }}" alt="logo TI" style="width: 80px; margin-bottom: 20px;">

        <h2 class="login-title">SppKita</h2>
        <p class="login-sub">Silakan masuk untuk melanjutkan</p>

        <form action="/login/proses" method="post" class="mb-4">
            @csrf

            <div class="mb-3 text-start">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username..." required autocomplete="off">
            </div>

            <div class="mb-3 text-start">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Masukkan password..." required autocomplete="off">

                    <button type="button" class="toggle-btn" id="togglePassword" style="border: 1px solid #ddd; border-top-right-radius: 8px; border-bottom-right-radius: 8px;">
                        <i class="bx bx-show"></i>
                    </button>
                </div>
            </div>

            <!-- Script toggle password -->
            <script>
                const password = document.getElementById("password");
                const togglePassword = document.getElementById("togglePassword");

                togglePassword.addEventListener("click", function() {
                    const type = password.type === "password" ? "text" : "password";
                    password.type = type;

                    const icon = this.querySelector("i");
                    icon.classList.toggle("bx-show");
                    icon.classList.toggle("bx-hide");
                });
            </script>

            <button type="submit" class="btn btn-primary w-100 btn-login">Login</button>
        </form>
    </div>

    @if ($pesan = Session::get('success'))
        <script>
            alert('{{ $pesan }}');
        </script>
    @endif

    @if ($pesan = Session::get('error'))
        <script>
            alert('{{ $pesan }}');
        </script>
    @endif

</body>

</html>
