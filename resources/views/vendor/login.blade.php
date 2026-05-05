<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Login - Kantin Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>
    <div class="login-wrapper">
        <div class="login-card fade-in">
            <div class="login-icon">
                <span class="mdi mdi-store"></span>
            </div>
            <h3>Vendor Login</h3>
            <p class="subtitle">Masuk ke dashboard vendor</p>

            <form action="{{ route('vendor.login.process') }}" method="POST" id="loginForm">
                @csrf
                <div class="mb-4 text-start">
                    <label class="form-label fw-bold">Pilih Vendor</label>
                    <select name="idvendor" id="vendorLoginSelect" class="form-control" style="width:100%;" required>
                        <option value="">-- Pilih Vendor --</option>
                        @foreach($vendors as $v)
                        <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
                        @endforeach
                    </select>
                    @error('idvendor')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary-custom w-100" id="btnLogin">
                    <span class="mdi mdi-login"></span> Masuk
                </button>
            </form>

            <a href="{{ route('home') }}" class="d-block mt-3 text-muted text-decoration-none">
                <small><span class="mdi mdi-arrow-left"></span> Kembali</small>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#vendorLoginSelect').select2({
                placeholder: '-- Pilih Vendor --',
                allowClear: true
            });
        });

        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('btnLogin');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Masuk...';
        });
    </script>
</body>

</html>