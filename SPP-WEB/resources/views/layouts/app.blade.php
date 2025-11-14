<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link href="{{ asset('bootstrap/css/bootstrap.css') }}" rel="stylesheet">

    <style>
        @font-face {
            font-family: 'Elms Sans';
            src: url('{{ asset('font/static/ElmsSans-Regular.ttf') }}') format('truetype');
        }

        body {
            font-family: 'Elms Sans', sans-serif;
        }

        .nav-item{
            margin: 0 0.8rem;
        }

        .nav-link{
            color: black;
            transition: all 0.2s ease-in-out;
        }

        a.nav-link:hover{
            transform: translateY(-1px);
            color: black;
        }

        button.logout{
            color: black;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
        }

        button.logout:hover{
            background-color: #dc2345;
            color: white;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>

    @include('layouts.navbar')

    <div class="container py-3">
        @yield('content')
    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>

</body>

</html>
