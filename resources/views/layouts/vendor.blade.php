<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vendor Dashboard') - Kantin Online</title>

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
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="brand">
            <h4><span class="mdi mdi-food-fork-drink"></span> Kantin Online</h4>
            <small>Vendor Dashboard</small>
        </div>
        <ul class="nav-menu">
            <li>
                <a href="{{ route('vendor.menu.index') }}" class="{{ request()->routeIs('vendor.menu.*') ? 'active' : '' }}">
                    <span class="mdi mdi-food"></span>
                    Kelola Menu
                </a>
            </li>
            <li>
                <a href="{{ route('vendor.pesanan.index') }}" class="{{ request()->routeIs('vendor.pesanan.*') ? 'active' : '' }}">
                    <span class="mdi mdi-clipboard-list"></span>
                    Pesanan Lunas
                </a>
            </li>
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="mdi mdi-logout"></span>
                    Logout
                </a>
                <form id="logout-form" action="{{ route('vendor.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <h5 class="page-title">@yield('page-title', 'Dashboard')</h5>
            <div class="vendor-info">
                <span class="mdi mdi-store"></span>
                {{ session('vendor_nama', 'Vendor') }}
            </div>
        </div>

        <!-- Content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

        function formatRupiah(num) {
            return 'Rp ' + Number(num).toLocaleString('id-ID');
        }
    </script>

    @stack('scripts')
</body>
</html>
