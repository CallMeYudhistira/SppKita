<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('boxicons/css/boxicons.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/5.3.5/apexcharts.min.css"
        integrity="sha512-IqtQ7LKr3He47p7HjxynmqZfN07VljNkdGyGDdDJ//f1r6bT0IEKQf2CCtSgun/pvbFlNnPDMRrMSQhmSxmSSg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/5.3.5/apexcharts.min.js"
        integrity="sha512-dC9VWzoPczd9ppMRE/FJohD2fB7ByZ0VVLVCMlOrM2LHqoFFuVGcWch1riUcwKJuhWx8OhPjhJsAHrp4CP4gtw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
        @font-face {
            font-family: 'Elms Sans';
            src: url('{{ asset('font/static/ElmsSans-Regular.ttf') }}') format('truetype');
        }

        body {
            font-family: 'Elms Sans', sans-serif;
        }

        h1,
        h2 {
            font-weight: 600;
        }

        .nav-link {
            color: black;
            transition: all 0.2s ease-in-out;
        }

        a.nav-link:hover {
            transform: translateY(-1px);
            color: black;
        }

        button.logout {
            color: black;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
        }

        .dropdown {
            transition: all 0.3s ease-in-out;
        }

        .dropdown:hover {
            transform: translateY(-1px);
        }

        .dropdown-toggle:focus,
        .dropdown-toggle:active {
            box-shadow: none !important;
            outline: none !important;
            border-color: transparent !important;
        }

        .dropdown.show .dropdown-toggle {
            border-color: transparent !important;
            box-shadow: none !important;
            background-color: transparent !important;
        }

        button.logout:hover {
            background-color: #dc2345;
            color: white;
            transform: translateY(-1px);
        }

        #content {
            position: relative;
            height: 100vh;
            width: calc(100% - 280px);
        }
    </style>
</head>

<body>

    @yield('navbar')

    <div class="container-fluid p-5" id="content" style="margin-left: 280px;">
        @yield('content')

        <div style="height: 5vh;"></div>
    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    @if ($errors->any())
        <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Error Validation ⚠️</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var myModal = new bootstrap.Modal(document.getElementById('errorModal'));
                myModal.show();
            });
        </script>
    @endif

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
