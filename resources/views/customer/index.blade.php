@extends('layouts.app')

@section('title', 'Pesan Makanan - Kantin Online')

@section('content')
<div class="container py-4">
    <!-- Vendor Selection -->
    <div class="row mb-4 fade-in">
        <div class="col-lg-8">
            <div class="card-custom p-4">
                <h5 class="fw-bold mb-3">
                    <span class="mdi mdi-store-search" style="color:var(--primary);"></span>
                    Pilih Vendor
                </h5>
                <select id="vendorSelect" class="form-control" style="width:100%;">
                    <option value="">-- Pilih Vendor --</option>
                    @foreach($vendors as $v)
                        <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-custom p-4" style="background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;">
                <h6 class="fw-bold mb-1"><span class="mdi mdi-information"></span> Cara Pesan</h6>
                <small>
                    1. Pilih vendor<br>
                    2. Tambah menu ke keranjang<br>
                    3. Klik "Buat Pesanan"<br>
                    4. Bayar via VA / QRIS
                </small>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Menu Grid -->
        <div class="col-lg-8 mb-4">
            <div id="menuContainer">
                <div class="card-custom p-5 text-center" id="menuPlaceholder">
                    <span class="mdi mdi-food-variant" style="font-size:4rem;color:var(--primary-light);opacity:0.3;"></span>
                    <p class="text-muted mt-2 mb-0">Pilih vendor untuk melihat menu</p>
                </div>
                <div id="menuLoading" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted mt-2">Memuat menu...</p>
                </div>
                <div id="menuGrid" class="pos-menu-grid d-none"></div>
            </div>
        </div>

        <!-- Cart Panel -->
        <div class="col-lg-4">
            <div class="cart-panel">
                <div class="cart-header">
                    <span class="mdi mdi-cart"></span>
                    Keranjang (<span id="cartCount">0</span>)
                </div>
                <div id="cartEmpty" class="cart-empty">
                    <span class="mdi mdi-cart-outline"></span>
                    <p>Keranjang masih kosong</p>
                </div>
                <div id="cartBody" class="cart-body d-none"></div>
                <div id="cartFooter" class="cart-footer d-none">
                    <div class="cart-total">
                        <span>Total</span>
                        <span class="total-amount" id="cartTotal">Rp 0</span>
                    </div>
                    <button type="button" class="btn btn-primary-custom w-100" id="btnPesan" onclick="submitOrder()">
                        <span class="mdi mdi-check-circle"></span> Buat Pesanan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let cart = {};
    let menuData = [];

    // Init Select2
    $(document).ready(function() {
        $('#vendorSelect').select2({
            placeholder: '-- Pilih Vendor --',
            allowClear: true
        });

        $('#vendorSelect').on('change', function() {
            const vendorId = $(this).val();
            if (vendorId) {
                loadMenu(vendorId);
            } else {
                showPlaceholder();
            }
        });
    });

    function showPlaceholder() {
        document.getElementById('menuPlaceholder').classList.remove('d-none');
        document.getElementById('menuGrid').classList.add('d-none');
        document.getElementById('menuLoading').classList.add('d-none');
    }

    function loadMenu(vendorId) {
        document.getElementById('menuPlaceholder').classList.add('d-none');
        document.getElementById('menuGrid').classList.add('d-none');
        document.getElementById('menuLoading').classList.remove('d-none');

        // Reset cart when changing vendor
        cart = {};
        renderCart();

        axios.get('/api/menu/' + vendorId)
            .then(function(response) {
                menuData = response.data;
                renderMenu(menuData);
            })
            .catch(function(error) {
                Swal.fire('Error', 'Gagal memuat menu', 'error');
                showPlaceholder();
            });
    }

    function renderMenu(menus) {
        document.getElementById('menuLoading').classList.add('d-none');
        const grid = document.getElementById('menuGrid');
        grid.classList.remove('d-none');

        if (menus.length === 0) {
            grid.innerHTML = '<div class="col-12 text-center py-5"><p class="text-muted">Belum ada menu tersedia</p></div>';
            return;
        }

        let html = '';
        menus.forEach(function(menu) {
            const inCart = cart[menu.idmenu] ? 'in-cart' : '';
            const qty = cart[menu.idmenu] ? cart[menu.idmenu].qty : 0;
            const catatan = cart[menu.idmenu] ? cart[menu.idmenu].catatan : '';

            html += `
                <div class="menu-card fade-in ${inCart}" id="menuCard-${menu.idmenu}">
                    <div class="menu-name">${menu.nama_menu}</div>
                    <div class="menu-price">${formatRupiah(menu.harga)}</div>
                    <div class="menu-stock">
                        <span class="mdi mdi-package-variant"></span> Stok: ${menu.stok}
                    </div>
                    <div class="qty-controls">
                        <button class="btn btn-danger-custom btn-sm" onclick="updateQty(${menu.idmenu}, -1)" ${qty === 0 ? 'disabled' : ''}>
                            <span class="mdi mdi-minus"></span>
                        </button>
                        <span class="qty-display" id="qty-${menu.idmenu}">${qty}</span>
                        <button class="btn btn-success-custom btn-sm" onclick="updateQty(${menu.idmenu}, 1)" ${qty >= menu.stok ? 'disabled' : ''}>
                            <span class="mdi mdi-plus"></span>
                        </button>
                    </div>
                    <input type="text" class="catatan-input" placeholder="Catatan (opsional)..."
                        id="catatan-${menu.idmenu}" value="${catatan}"
                        onchange="updateCatatan(${menu.idmenu}, this.value)">
                </div>
            `;
        });
        grid.innerHTML = html;
    }

    function updateQty(idmenu, delta) {
        const menu = menuData.find(m => m.idmenu === idmenu);
        if (!menu) return;

        if (!cart[idmenu]) {
            cart[idmenu] = { idmenu: idmenu, nama: menu.nama_menu, harga: menu.harga, qty: 0, catatan: '', stok: menu.stok };
        }

        cart[idmenu].qty += delta;

        if (cart[idmenu].qty <= 0) {
            delete cart[idmenu];
        } else if (cart[idmenu].qty > menu.stok) {
            cart[idmenu].qty = menu.stok;
            Swal.fire({ icon: 'warning', title: 'Stok tidak cukup', text: `Maksimal ${menu.stok} item`, timer: 2000, showConfirmButton: false });
        }

        renderMenu(menuData);
        renderCart();
    }

    function updateCatatan(idmenu, value) {
        if (cart[idmenu]) {
            cart[idmenu].catatan = value;
        }
    }

    function removeFromCart(idmenu) {
        delete cart[idmenu];
        renderMenu(menuData);
        renderCart();
    }

    function renderCart() {
        const items = Object.values(cart);
        const countEl = document.getElementById('cartCount');
        const emptyEl = document.getElementById('cartEmpty');
        const bodyEl = document.getElementById('cartBody');
        const footerEl = document.getElementById('cartFooter');
        const totalEl = document.getElementById('cartTotal');

        countEl.textContent = items.length;

        if (items.length === 0) {
            emptyEl.classList.remove('d-none');
            bodyEl.classList.add('d-none');
            footerEl.classList.add('d-none');
            return;
        }

        emptyEl.classList.add('d-none');
        bodyEl.classList.remove('d-none');
        footerEl.classList.remove('d-none');

        let total = 0;
        let html = '';
        items.forEach(function(item) {
            const subtotal = item.harga * item.qty;
            total += subtotal;
            html += `
                <div class="cart-item">
                    <div class="item-info">
                        <div class="item-name">${item.nama}</div>
                        <div class="item-qty">${item.qty}x ${formatRupiah(item.harga)}</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="item-subtotal">${formatRupiah(subtotal)}</span>
                        <button class="btn-remove" onclick="removeFromCart(${item.idmenu})">
                            <span class="mdi mdi-close-circle"></span>
                        </button>
                    </div>
                </div>
            `;
        });

        bodyEl.innerHTML = html;
        totalEl.textContent = formatRupiah(total);
    }

    function submitOrder() {
        const items = Object.values(cart);
        if (items.length === 0) {
            Swal.fire('Oops!', 'Keranjang masih kosong', 'warning');
            return;
        }

        const vendorId = $('#vendorSelect').val();
        if (!vendorId) {
            Swal.fire('Oops!', 'Pilih vendor terlebih dahulu', 'warning');
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Pesanan',
            text: 'Apakah Anda yakin ingin membuat pesanan ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#7c3aed',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Pesan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                processOrder(vendorId, items);
            }
        });
    }

    function processOrder(vendorId, items) {
        const btn = document.getElementById('btnPesan');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';

        const orderItems = items.map(item => ({
            idmenu: item.idmenu,
            jumlah: item.qty,
            catatan: item.catatan || null
        }));

        axios.post('/api/pesan', {
            idvendor: vendorId,
            items: orderItems
        })
        .then(function(response) {
            if (response.data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Pesanan Berhasil!',
                    text: 'ID Customer: ' + response.data.idcustomer,
                    confirmButtonColor: '#7c3aed',
                }).then(() => {
                    window.location.href = '/payment/' + response.data.idpesanan;
                });
            }
        })
        .catch(function(error) {
            const msg = error.response?.data?.message || 'Terjadi kesalahan';
            Swal.fire('Error', msg, 'error');
        })
        .finally(function() {
            btn.disabled = false;
            btn.innerHTML = '<span class="mdi mdi-check-circle"></span> Buat Pesanan';
        });
    }
</script>
@endpush
