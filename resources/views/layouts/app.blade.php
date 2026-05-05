<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Kantin Online')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-customer">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <span class="mdi mdi-food-fork-drink"></span>
                Kantin Online
            </a>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-light" style="border-radius:8px;">
                    <span class="mdi mdi-power"></span> Keluar
                </a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-customer">
        <p class="mb-0">&copy; {{ date('Y') }} Sistem Kantin Online. All rights reserved.</p>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        // Setup Axios CSRF
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

        // Format currency
        function formatRupiah(num) {
            return 'Rp ' + Number(num).toLocaleString('id-ID');
        }
    </script>

    @stack('scripts')
</body>

</html>