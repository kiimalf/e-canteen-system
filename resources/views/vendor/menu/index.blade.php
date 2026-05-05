@extends('layouts.vendor')

@section('title', 'Kelola Menu')
@section('page-title', 'Kelola Menu')

@section('content')
<div class="card-custom fade-in">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <span><span class="mdi mdi-food"></span> Daftar Menu</span>
        <button class="btn btn-sm btn-light fw-bold" onclick="addMenu()" style="border-radius:8px;">
            <span class="mdi mdi-plus-circle"></span> Tambah Menu
        </button>
    </div>
    <div class="p-3">
        <div class="table-responsive">
            <table class="table table-custom mb-0" id="menuTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Menu</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th style="width:180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $i => $menu)
                    <tr id="row-{{ $menu->idmenu }}">
                        <td>{{ $i + 1 }}</td>
                        <td class="fw-bold">{{ $menu->nama_menu }}</td>
                        <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $menu->stok > 0 ? 'bg-success' : 'bg-danger' }}" style="border-radius:20px;padding:6px 14px;">
                                {{ $menu->stok }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-warning-custom btn-sm" onclick="editMenu({{ $menu->idmenu }}, '{{ addslashes($menu->nama_menu) }}', {{ $menu->harga }}, {{ $menu->stok }})">
                                <span class="mdi mdi-pencil"></span>
                            </button>
                            <button class="btn btn-danger-custom btn-sm" onclick="deleteMenu({{ $menu->idmenu }}, '{{ addslashes($menu->nama_menu) }}')">
                                <span class="mdi mdi-delete"></span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="5" class="text-center text-muted py-4">
                            <span class="mdi mdi-food-off" style="font-size:2rem;display:block;opacity:0.3;"></span>
                            Belum ada menu. Klik "Tambah Menu" untuk mulai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function addMenu() {
        Swal.fire({
            title: 'Tambah Menu Baru',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Menu</label>
                        <input type="text" id="swal-nama" class="form-control" placeholder="Nama menu...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Harga (Rp)</label>
                        <input type="number" id="swal-harga" class="form-control" placeholder="0" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Stok</label>
                        <input type="number" id="swal-stok" class="form-control" placeholder="0" min="0">
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonColor: '#7c3aed',
            confirmButtonText: '<span class="mdi mdi-check"></span> Simpan',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const nama = document.getElementById('swal-nama').value;
                const harga = document.getElementById('swal-harga').value;
                const stok = document.getElementById('swal-stok').value;
                if (!nama || !harga || !stok) {
                    Swal.showValidationMessage('Semua field harus diisi!');
                    return false;
                }
                return {
                    nama_menu: nama,
                    harga: parseInt(harga),
                    stok: parseInt(stok)
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post('{{ route("vendor.menu.store") }}', result.value)
                    .then(res => {
                        Swal.fire({
                                icon: 'success',
                                title: res.data.message,
                                confirmButtonColor: '#7c3aed'
                            })
                            .then(() => location.reload());
                    })
                    .catch(err => {
                        const msg = err.response?.data?.message || 'Gagal menambah menu';
                        Swal.fire('Error', msg, 'error');
                    });
            }
        });
    }

    function editMenu(id, nama, harga, stok) {
        Swal.fire({
            title: 'Edit Menu',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Menu</label>
                        <input type="text" id="swal-nama" class="form-control" value="${nama}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Harga (Rp)</label>
                        <input type="number" id="swal-harga" class="form-control" value="${harga}" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Stok</label>
                        <input type="number" id="swal-stok" class="form-control" value="${stok}" min="0">
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonColor: '#7c3aed',
            confirmButtonText: '<span class="mdi mdi-check"></span> Update',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const n = document.getElementById('swal-nama').value;
                const h = document.getElementById('swal-harga').value;
                const s = document.getElementById('swal-stok').value;
                if (!n || !h || !s) {
                    Swal.showValidationMessage('Semua field harus diisi!');
                    return false;
                }
                return {
                    nama_menu: n,
                    harga: parseInt(h),
                    stok: parseInt(s)
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                axios.put('/vendor/menu/' + id, result.value)
                    .then(res => {
                        Swal.fire({
                                icon: 'success',
                                title: res.data.message,
                                confirmButtonColor: '#7c3aed'
                            })
                            .then(() => location.reload());
                    })
                    .catch(err => {
                        Swal.fire('Error', 'Gagal mengupdate menu', 'error');
                    });
            }
        });
    }

    function deleteMenu(id, nama) {
        Swal.fire({
            title: 'Hapus Menu?',
            text: `Apakah Anda yakin ingin menghapus "${nama}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<span class="mdi mdi-delete"></span> Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete('/vendor/menu/' + id)
                    .then(res => {
                        Swal.fire({
                                icon: 'success',
                                title: res.data.message,
                                confirmButtonColor: '#7c3aed'
                            })
                            .then(() => location.reload());
                    })
                    .catch(err => {
                        Swal.fire('Error', 'Gagal menghapus menu', 'error');
                    });
            }
        });
    }

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: '{{ session('success') }}',
        confirmButtonColor: '#7c3aed'
    });
    @endif
</script>
@endpush