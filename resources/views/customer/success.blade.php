@extends('layouts.app')

@section('title', 'Pesanan Berhasil - Kantin Online')

@section('content')
<div class="container py-4">
    <div class="success-card card-custom p-5 fade-in">
        <div class="success-icon">
            <span class="mdi mdi-check-bold"></span>
        </div>
        <h3 class="fw-bold mb-2">Pembayaran Berhasil!</h3>
        <p class="text-muted mb-4">Pesanan Anda telah dikonfirmasi dan sedang diproses.</p>

        <div class="text-start mx-auto" style="max-width:400px;">
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">No. Pesanan</span>
                <strong>#{{ $pesanan->idpesanan }}</strong>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Customer ID</span>
                <strong>{{ $pesanan->customer->idcustomer }}</strong>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Vendor</span>
                <strong>{{ $pesanan->vendor->nama_vendor }}</strong>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Status</span>
                <span class="badge-{{ $pesanan->status }}">{{ ucfirst($pesanan->status) }}</span>
            </div>
            @if($pesanan->payment)
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">Metode Bayar</span>
                <strong>{{ $pesanan->payment->metode_bayar }}</strong>
            </div>
            @endif

            <h6 class="fw-bold mt-3 mb-2">Detail Pesanan</h6>
            @foreach($pesanan->detailPesanan as $detail)
            <div class="d-flex justify-content-between py-1">
                <span>{{ $detail->menu->nama_menu }} <small class="text-muted">x{{ $detail->jumlah }}</small></span>
                <strong>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</strong>
            </div>
            @endforeach

            <div class="d-flex justify-content-between py-2 border-top mt-2" style="font-size:1.2rem;">
                <strong>Total</strong>
                <strong style="color:var(--primary);">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</strong>
            </div>
        </div>

        <div class="text-center mt-4 border-top pt-4">
            <h6 class="fw-bold mb-2">QR Code Pesanan</h6>
            <p class="text-muted small mb-3">Simpan QR Code ini untuk ditunjukkan kepada vendor.</p>
            <div class="bg-white p-2 d-inline-block rounded shadow-sm mb-3">
                <img src="{{ $qrCodeDataUri }}" alt="QR Code Pesanan" style="max-width: 200px; height: auto;">
            </div>
            <br>
            <a href="{{ $qrCodeDataUri }}" download="Kantin-Order-{{ $pesanan->idpesanan }}.png" class="btn btn-outline-primary btn-sm">
                <span class="mdi mdi-download"></span> Download QR Code
            </a>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('customer.index') }}" class="btn btn-primary-custom">
                <span class="mdi mdi-cart-plus"></span> Pesan Lagi
            </a>
        </div>
    </div>
</div>
@endsection
