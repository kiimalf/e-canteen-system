@extends('layouts.vendor')

@section('title', 'Scan QR Code Pesanan')
@section('page-title', 'Scan QR Code Pesanan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card-custom fade-in">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <div><span class="mdi mdi-qrcode-scan"></span> Scan QR Code Customer</div>
                <a href="{{ route('vendor.pesanan.index') }}" class="btn btn-sm btn-light rounded-pill px-3 shadow-sm text-primary fw-bold" style="transition: all 0.3s ease;">
                    <span class="mdi mdi-arrow-left"></span> Kembali
                </a>
            </div>
            <div class="p-4 text-center">
                <div id="reader-container" class="mb-4">
                    <div id="reader" style="width: 100%; max-width: 500px; margin: 0 auto; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);"></div>
                </div>

                <div id="scan-result" style="display: none;" class="text-start">
                    <div class="alert alert-success d-flex align-items-center mb-4 rounded-3 border-0 shadow-sm" style="background-color: #d1fae5; color: #065f46;">
                        <span class="mdi mdi-check-circle-outline fs-3 me-2"></span>
                        <div>
                            <strong>Scan Berhasil!</strong><br>
                            Menampilkan detail pesanan.
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
                        <div class="card-body p-4" id="detailContent">
                            <!-- Data injected here -->
                        </div>
                    </div>

                    <button class="btn btn-primary-custom btn-lg w-100 rounded-pill shadow fw-bold" id="scan-again-btn" onclick="resetScanner()" style="letter-spacing: 0.5px; transition: all 0.3s ease;">
                        <span class="mdi mdi-qrcode-scan me-1"></span> Scan Ulang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Audio for Beep -->
<audio id="beep-sound" src="https://actions.google.com/sounds/v1/alarms/beep_short.ogg" preload="auto"></audio>

@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let html5QrcodeScanner;

    document.addEventListener("DOMContentLoaded", function() {
        startScanner();
    });

    function startScanner() {
        html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                }
            },
            /* verbose= */
            false
        );
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Stop scanner
        html5QrcodeScanner.clear().then(() => {
            // Play beep
            document.getElementById('beep-sound').play();

            // Show result section and hide reader
            document.getElementById('reader-container').style.display = 'none';
            document.getElementById('scan-result').style.display = 'block';

            const content = document.getElementById('detailContent');
            content.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Memuat data pesanan...</p></div>';

            // Fetch detail
            axios.get('/vendor/pesanan/' + decodedText)
                .then(function(res) {
                    const p = res.data;
                    let itemsHtml = '';
                    if (p.detail_pesanan) {
                        p.detail_pesanan.forEach(function(d) {
                            itemsHtml += `
                                <div class="d-flex justify-content-between py-2 border-bottom">
                                    <div>
                                        <strong>${d.menu ? d.menu.nama_menu : '-'}</strong><br>
                                        <small class="text-muted">${d.jumlah}x ${formatRupiah(d.harga)}</small>
                                        ${d.catatan ? '<br><small class="text-muted"><em>📝 ' + d.catatan + '</em></small>' : ''}
                                    </div>
                                    <strong style="color:var(--primary);">${formatRupiah(d.subtotal)}</strong>
                                </div>
                            `;
                        });
                    }

                    content.innerHTML = `
                        <div class="mb-3">
                            <div class="d-flex justify-content-between py-1 border-bottom">
                                <span class="text-muted">No. Pesanan</span>
                                <strong>#${p.idpesanan}</strong>
                            </div>
                            <div class="d-flex justify-content-between py-1 border-bottom">
                                <span class="text-muted">Customer</span>
                                <strong>${p.customer ? p.customer.idcustomer : '-'}</strong>
                            </div>
                            <div class="d-flex justify-content-between py-1 border-bottom">
                                <span class="text-muted">Status</span>
                                <span class="badge-${p.status}">${p.status.toUpperCase()}</span>
                            </div>
                            <div class="d-flex justify-content-between py-1 border-bottom">
                                <span class="text-muted">Metode Bayar</span>
                                <strong>${p.payment ? p.payment.metode_bayar : '-'}</strong>
                            </div>
                        </div>
                        <h6 class="fw-bold mt-4 mb-3">Item Pesanan</h6>
                        ${itemsHtml}
                        <div class="d-flex justify-content-between py-3 border-top mt-2" style="font-size:1.15rem;">
                            <strong>Total</strong>
                            <strong style="color:var(--primary);">${formatRupiah(p.total)}</strong>
                        </div>
                    `;
                })
                .catch(function(err) {
                    content.innerHTML = '<div class="text-center text-danger py-4"><span class="mdi mdi-alert-circle-outline fs-1"></span><p class="mt-2">Gagal memuat detail pesanan. Pesanan mungkin bukan dari vendor ini atau ID tidak valid.</p></div>';
                });
        }).catch(error => {
            console.error("Failed to clear html5QrcodeScanner. ", error);
        });
    }

    function onScanFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning.
    }

    function resetScanner() {
        document.getElementById('scan-result').style.display = 'none';
        document.getElementById('reader-container').style.display = 'block';
        startScanner();
    }
</script>
@endpush