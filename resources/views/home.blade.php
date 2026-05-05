<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kantin Online - Pilih Peran</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f3e8ff, #ffffff);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        .card-custom {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(124, 58, 237, 0.1);
            transition: transform 0.3s ease;
            background: #fff;
        }
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(124, 58, 237, 0.15);
        }
        .icon-box {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(124, 58, 237, 0.1);
            color: #7c3aed;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            margin: 0 auto 20px;
        }
        .btn-custom {
            background-color: #7c3aed;
            color: #fff;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn-custom:hover {
            background-color: #6d28d9;
            color: #fff;
        }
        .btn-vendor {
            background-color: #10b981;
            color: #fff;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn-vendor:hover {
            background-color: #059669;
            color: #fff;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 position-relative" style="overflow: hidden;">
    <!-- Abstract Background Decor -->
    <div style="position: absolute; top: -100px; left: -100px; width: 300px; height: 300px; background: rgba(124, 58, 237, 0.1); border-radius: 50%; z-index: -1;"></div>
    <div style="position: absolute; bottom: -150px; right: -50px; width: 400px; height: 400px; background: rgba(16, 185, 129, 0.05); border-radius: 50%; z-index: -1;"></div>

    <div class="container text-center">
        <h1 class="fw-bold mb-3" style="color: #4c1d95; font-size: 2.8rem;"><span class="mdi mdi-food-fork-drink"></span> Kantin Online</h1>
        <p class="text-muted mb-5" style="font-size: 1.1rem;">Selamat datang! Silakan pilih peran Anda untuk masuk ke sistem.</p>
        
        <div class="row justify-content-center gap-4">
            <div class="col-md-4 col-sm-10">
                <a href="{{ route('customer.index') }}" class="text-decoration-none text-dark d-block">
                    <div class="card card-custom p-5 h-100">
                        <div class="icon-box">
                            <span class="mdi mdi-account-group"></span>
                        </div>
                        <h4 class="fw-bold mb-2">Customer</h4>
                        <p class="text-muted small">Pesan makanan dari berbagai tenant kantin dengan mudah dan cepat tanpa antre panjang.</p>
                        <div class="mt-auto pt-4">
                            <button class="btn btn-custom w-100">Masuk sebagai Customer</button>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-10">
                <a href="{{ route('vendor.login') }}" class="text-decoration-none text-dark d-block">
                    <div class="card card-custom p-5 h-100">
                        <div class="icon-box" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                            <span class="mdi mdi-store"></span>
                        </div>
                        <h4 class="fw-bold mb-2">Vendor</h4>
                        <p class="text-muted small">Kelola menu hidangan, pantau pesanan pelanggan, dan konfirmasi pembayaran secara real-time.</p>
                        <div class="mt-auto pt-4">
                            <button class="btn btn-vendor w-100">Masuk sebagai Vendor</button>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="mt-5 text-muted small">
            &copy; {{ date('Y') }} Sistem Kantin Online. All rights reserved.
        </div>
    </div>
</body>
</html>
