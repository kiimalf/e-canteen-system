@extends('layouts.app')

@section('title', 'Pembayaran - Kantin Online')

@section('content')
<div class="container py-4">
    <div class="payment-card fade-in">
        <div class="payment-header">
            <h3><span class="mdi mdi-credit-card-check"></span> Pembayaran</h3>
            <p class="mb-0">Pesanan #{{ $pesanan->idpesanan }} — {{ $pesanan->customer->idcustomer }}</p>
        </div>
        <div class="payment-body">
            <h6 class="fw-bold mb-3">
                <span class="mdi mdi-store" style="color:var(--primary);"></span>
                {{ $pesanan->vendor->nama_vendor }}
            </h6>

            <div class="payment-items mb-3">
                @foreach($pesanan->detailPesanan as $detail)
                <div class="item-row">
                    <div>
                        <strong>{{ $detail->menu->nama_menu }}</strong>
                        <small class="text-muted d-block">{{ $detail->jumlah }}x {{ number_format($detail->harga, 0, ',', '.') }}</small>
                        @if($detail->catatan)
                            <small class="text-muted"><em>📝 {{ $detail->catatan }}</em></small>
                        @endif
                    </div>
                    <strong>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</strong>
                </div>
                @endforeach
            </div>

            <div class="payment-total">
                <span>Total Bayar</span>
                <span class="amount">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span>
            </div>

            @if($snapToken)
                <button type="button" class="btn btn-primary-custom w-100 mt-3" id="btnBayar" onclick="payWithMidtrans()">
                    <span class="mdi mdi-cash-register"></span> Bayar Sekarang
                </button>
            @else
                <div class="alert alert-warning mt-3 text-center">
                    <span class="mdi mdi-alert-circle"></span>
                    <strong>Midtrans belum dikonfigurasi.</strong><br>
                    <small>Gunakan pembayaran manual untuk testing.</small>
                </div>
                <button type="button" class="btn btn-success-custom w-100 mt-2" id="btnManual" onclick="manualPay()">
                    <span class="mdi mdi-check-decagram"></span> Bayar Manual (Demo)
                </button>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($snapToken)
<script src="{{ $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ $clientKey }}"></script>
<script>
    function payWithMidtrans() {
        const btn = document.getElementById('btnBayar');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';

        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran Berhasil!',
                    text: 'Terima kasih, pesanan Anda telah dibayar.',
                    confirmButtonColor: '#7c3aed',
                }).then(() => {
                    window.location.href = '/success/{{ $pesanan->idpesanan }}';
                });
            },
            onPending: function(result) {
                Swal.fire({
                    icon: 'info',
                    title: 'Menunggu Pembayaran',
                    text: 'Silakan selesaikan pembayaran Anda.',
                    confirmButtonColor: '#7c3aed',
                });
                btn.disabled = false;
                btn.innerHTML = '<span class="mdi mdi-cash-register"></span> Bayar Sekarang';
            },
            onError: function(result) {
                Swal.fire('Error', 'Pembayaran gagal. Silakan coba lagi.', 'error');
                btn.disabled = false;
                btn.innerHTML = '<span class="mdi mdi-cash-register"></span> Bayar Sekarang';
            },
            onClose: function() {
                btn.disabled = false;
                btn.innerHTML = '<span class="mdi mdi-cash-register"></span> Bayar Sekarang';
            }
        });
    }
</script>
@else
<script>
    function manualPay() {
        const btn = document.getElementById('btnManual');

        Swal.fire({
            title: 'Pilih Metode Pembayaran',
            input: 'select',
            inputOptions: { 'VA': 'Virtual Account (VA)', 'QRIS': 'QRIS' },
            inputPlaceholder: 'Pilih metode...',
            showCancelButton: true,
            confirmButtonColor: '#7c3aed',
            confirmButtonText: 'Bayar',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) return 'Pilih metode pembayaran!';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';

                axios.post('/payment/manual/{{ $pesanan->idpesanan }}', {
                    metode_bayar: result.value
                })
                .then(function(response) {
                    if (response.data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Berhasil!',
                            confirmButtonColor: '#7c3aed',
                        }).then(() => {
                            window.location.href = '/success/{{ $pesanan->idpesanan }}';
                        });
                    }
                })
                .catch(function() {
                    Swal.fire('Error', 'Gagal memproses pembayaran', 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<span class="mdi mdi-check-decagram"></span> Bayar Manual (Demo)';
                });
            }
        });
    }
</script>
@endif
@endpush
