# 🍱 Smart Canteen System (Kantin Online)

Platform pemesanan makanan digital yang modern untuk ekosistem kantin, mendukung transaksi cashless dengan integrasi Midtrans dan verifikasi pesanan menggunakan QR Code.

---

## 🚀 Tech Stack

| Layer | Teknologi |
| :--- | :--- |
| **Backend** | PHP 8.1+, Laravel 10 |
| **Frontend** | Blade Templating, Vanilla CSS, JavaScript (ES6) |
| **Database** | MySQL |
| **Payment Gateway** | Midtrans API (Snap & Notification) |
| **UI Components** | SweetAlert2, Select2, Material Design Icons |
| **Utilities** | Html5-QRCode (Scanner), Axios (AJAX), QR Code Generator |

---

## 🧠 Arsitektur

### Struktur Direktori
```text
sistem_kantin/
├── app/
│   ├── Http/Controllers/    # Logika bisnis & pengelolaan request
│   └── Models/               # Representasi tabel database
├── database/
│   ├── migrations/          # Struktur skema tabel
│   └── seeders/             # Data awal untuk testing
├── public/                  # Asset statis (CSS, JS, Images)
├── resources/
│   └── views/               # Template tampilan Blade
│       ├── customer/        # UI untuk pembeli
│       ├── vendor/          # UI untuk penjual/admin kantin
│       └── layouts/         # Template master
└── routes/
    └── web.php              # Definisi rute aplikasi
```

- `app/Http/Controllers/` → Berisi logic untuk proses checkout, integrasi Midtrans, dan verifikasi QR Code.
- `resources/views/` → Berisi seluruh file tampilan yang dipisahkan berdasarkan peran user (Customer/Vendor).
- `routes/web.php` → Mengatur navigasi antar halaman dan endpoint untuk webhook Midtrans.
- `database/migrations/` → Definisi teknis tabel `vendors`, `menus`, `pesanans`, dan `detail_pesanans`.

---

## 🔑 Fitur Utama

### 👤 Role: Customer (Guest)
- **Guest Ordering:** Pesan makanan tanpa perlu pendaftaran akun.
- **Smart Menu Search:** Pencarian menu cepat menggunakan Select2.
- **Integrated Payment:** Pembayaran otomatis via QRIS, VA, atau E-Wallet melalui Midtrans.
- **Digital Receipt:** Bukti pesanan berupa QR Code yang dapat diunduh/disimpan.

### 🏪 Role: Vendor (Admin Kantin)
- **Vendor Dashboard:** Statistik penjualan dan status pesanan masuk.
- **Menu Management:** Kelola stok, harga, dan foto menu makanan/minuman.
- **QR Order Scanner:** Verifikasi pesanan pelanggan secara real-time menggunakan kamera.
- **Transaction Logs:** Lacak riwayat pembayaran yang sudah diverifikasi oleh sistem.

---

## 🔄 Flow Sistem

### 🛒 Alur Pelanggan (Customer)
1. Pelanggan membuka aplikasi dan memilih menu dari vendor tertentu.
2. Memasukkan item ke keranjang dan menekan tombol **Bayar**.
3. Sistem mengarahkan ke portal pembayaran Midtrans.
4. Setelah bayar, sistem akan menampilkan halaman sukses beserta **QR Code unik**.
5. Pelanggan menyimpan QR Code tersebut untuk ditunjukkan ke vendor.

### 👨‍🍳 Alur Penjual (Vendor)
1. Vendor login ke dashboard menggunakan akun yang terdaftar.
2. Vendor membuka menu **Scanner Pesanan**.
3. Vendor memindai (scan) QR Code yang dibawa oleh pelanggan.
4. Jika valid, sistem mengubah status pesanan menjadi "Diproses" atau "Selesai".
5. Vendor menyerahkan pesanan kepada pelanggan.

---

## 🗄️ Database Design

| Tabel | Keterangan |
| :--- | :--- |
| `vendors` | Menyimpan data profil toko/kantin. |
| `menus` | Berisi daftar makanan/minuman beserta harga dan stok. |
| `pesanans` | Master data transaksi, status bayar, dan total harga. |
| `detail_pesanans` | Rincian item menu yang dibeli dalam satu transaksi. |
| `payment_logs` | Catatan notifikasi webhook dari Midtrans. |

### Relasi Antar Tabel
```text
vendors
└── menus (1:N)
    └── detail_pesanans (N:1)
        └── pesanans (N:1)
                └── payment_logs (1:N)
```

---

## 🔐 Keamanan

- **CSRF Protection:** Melindungi setiap form dari serangan Cross-Site Request Forgery.
- **Midtrans Signature Key:** Verifikasi keaslian data notifikasi pembayaran dari server Midtrans.
- **Password Hashing:** Menggunakan Bcrypt untuk keamanan akun vendor.
- **Input Validation:** Validasi ketat pada setiap inputan user untuk mencegah SQL Injection.

---

## 📦 Setup & Instalasi

1. **Clone Project**
```bash
git clone https://github.com/[username]/sistem-kantin.git
cd sistem-kantin
```

2. **Install Dependencies**
```bash
composer install
npm install && npm run dev
```

3. **Konfigurasi Environment**
Buat file `.env` dan masukkan API Key Midtrans:
```env
DB_DATABASE=sistem_kantin
DB_USERNAME=root
DB_PASSWORD=

MIDTRANS_SERVER_KEY=SB-Mid-server-XXXXX
MIDTRANS_CLIENT_KEY=SB-Mid-client-XXXXX
MIDTRANS_IS_PRODUCTION=false
```

4. **Setup Database**
```bash
php artisan key:generate
php artisan migrate --seed
```

5. **Jalankan Aplikasi**
```bash
php artisan serve
```

---

## 🎨 Design System

| Status | Warna | Hex Code |
| :--- | :--- | :--- |
| **Success / Paid** | Green | `#1bcfb4` |
| **Pending** | Orange/Yellow | `#fed713` |
| **Failed / Error** | Red | `#fe7c96` |
| **Primary Theme** | Purple | `#b66dff` |

- **UI Style:** Modern Dashboard (Purple Admin inspiration), Glassmorphism elements, Responsive layout.

---

## 🐞 Known Issues

- [ ] Kamera scanner kadang tidak fokus pada beberapa browser mobile tertentu.
- [ ] Delay notifikasi dari Midtrans sandbox pada jam sibuk.

---

## 📌 Rencana Pengembangan

- [ ] Integrasi Push Notification untuk vendor saat pesanan masuk.
- [ ] Laporan penjualan dalam format Excel/PDF bulanan.
- [ ] Fitur diskon dan promo kode.

---

## 👨‍💻 Author

- **Nabil Hakim Alfikri** - Universitas Airlangga
- GitHub: [@kiimalf](https://github.com/kiimalf)


---

## 📜 Lisensi
Proyek ini dibuat untuk tujuan pembelajaran dalam Workshop Pengembangan Web.
