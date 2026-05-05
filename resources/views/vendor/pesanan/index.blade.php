@extends('layouts.vendor')

@section('title', 'Pesanan Lunas')
@section('page-title', 'Pesanan Lunas')

@section('content')
<div class="card-custom fade-in">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div>
            <span class="mdi mdi-clipboard-check"></span> Daftar Pesanan Lunas
        </div>
        <a href="{{ route('vendor.pesanan.scan') }}" class="btn btn-light btn-sm rounded-pill px-3 shadow-sm text-primary fw-bold" style="transition: all 0.3s ease;">
            <span class="mdi mdi-qrcode-scan me-1"></span> Scan QR Code
        </a>
    </div>
    <div class="p-3">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Pesanan</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Waktu</th>
                        <th>Status</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesanans as $i => $pesanan)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><strong>#{{ $pesanan->idpesanan }}</strong></td>
                        <td>{{ $pesanan->customer->idcustomer }}</td>
                        <td class="fw-bold" style="color:var(--primary);">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td>
                        <td>{{ $pesanan->created_at ? $pesanan->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td><span class="badge-lunas">Lunas</span></td>
                        <td>
                            <button class="btn btn-primary-custom btn-sm" onclick="showDetail('{{ $pesanan->idpesanan }}')">
                                <span class="mdi mdi-eye"></span> Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <span class="mdi mdi-clipboard-text-off" style="font-size:2rem;display:block;opacity:0.3;"></span>
                            Belum ada pesanan lunas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:var(--border-radius);border:none;">
            <div class="modal-header" style="background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;border:none;">
                <h5 class="modal-title fw-bold"><span class="mdi mdi-clipboard-list"></span> Detail Pesanan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showDetail(id) {
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        const content = document.getElementById('detailContent');
        content.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
        modal.show();

        axios.get('/vendor/pesanan/' + id)
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
                        <div class="d-flex justify-content-between py-1">
                            <span class="text-muted">No. Pesanan</span>
                            <strong>#${p.idpesanan}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <span class="text-muted">Customer</span>
                            <strong>${p.customer ? p.customer.idcustomer : '-'}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <span class="text-muted">Metode Bayar</span>
                            <strong>${p.payment ? p.payment.metode_bayar : '-'}</strong>
                        </div>
                    </div>
                    <h6 class="fw-bold">Item Pesanan</h6>
                    ${itemsHtml}
                    <div class="d-flex justify-content-between py-2 border-top mt-2" style="font-size:1.15rem;">
                        <strong>Total</strong>
                        <strong style="color:var(--primary);">${formatRupiah(p.total)}</strong>
                    </div>
                `;
            })
            .catch(function() {
                content.innerHTML = '<div class="text-center text-danger py-4">Gagal memuat detail pesanan</div>';
            });
    }
</script>
@endpush