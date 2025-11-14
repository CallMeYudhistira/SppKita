<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SPP</title>

    <link href="{{ asset('bootstrap/css/bootstrap.css') }}" rel="stylesheet">

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
        }

        .login-title {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 5px;
            text-align: center;
            color: #25396f;
        }

        .login-sub {
            text-align: center;
            color: #6c757d;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .form-control {
            border-radius: 10px;
            height: 48px;
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

        <h2 class="login-title">SppKita</h2>
        <p class="login-sub">Silakan masuk untuk melanjutkan</p>

        <form action="/login/proses" method="post" class="mb-4">
            @csrf

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username..." required
                    autocomplete="off">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Masukkan password..." required autocomplete="off">

                    <button type="button" class="btn btn-outline-secondary" style="border: 1px solid #ddd;" id="togglePassword">
                        👀
                    </button>
                </div>
            </div>

            <script>
                const password = document.getElementById("password");
                const togglePassword = document.getElementById("togglePassword");

                togglePassword.addEventListener("click", function() {
                    const type = password.getAttribute("type") === "password" ? "text" : "password";
                    password.setAttribute("type", type);

                    this.textContent = type === "password" ? "👀" : "🫣";
                });
            </script>


            <button type="submit" class="btn btn-primary w-100 btn-login">Login</button>
        </form>
    </div>

</body>

</html>
