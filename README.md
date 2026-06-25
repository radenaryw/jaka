# ☕ Djaka Coffee — Marketplace Web App

Sistem marketplace kafe lengkap dengan halaman customer, kasir, dan admin.

---

## 📁 Struktur File

```
djaka-coffee/
├── login.html       → Halaman login (customer/kasir/admin)
├── pembeli.html     → Halaman customer/pembeli
├── kasir.html       → Halaman kasir
├── admin.html       → Halaman admin
├── style.css        → Styling global
├── script.js        → Logic bersama (Cart, Stock, Orders, Session)
├── api.php          → REST API untuk koneksi ke MySQL
├── db_connect.php   → Konfigurasi database
├── setup_db.php     → Script setup database (jalankan sekali)
└── README.md        → Panduan ini
```

---

## 🚀 Cara Menjalankan

### 1. Prasyarat
- **XAMPP / WAMP / Laragon** (PHP + MySQL)
- Browser modern (Chrome, Firefox, Edge)

### 2. Instalasi

1. Letakkan folder `djaka-coffee` di `htdocs` (XAMPP) atau `www` (WAMP)
   ```
   C:/xampp/htdocs/djaka-coffee/
   ```

2. Jalankan Apache & MySQL dari XAMPP Control Panel

3. Buka browser → Setup database:
   ```
   http://localhost/djaka-coffee/setup_db.php
   ```
   Ini akan membuat database `djaka_coffee` beserta semua tabel secara otomatis.

4. Akses aplikasi:
   ```
   http://localhost/djaka-coffee/login.html
   ```

### 3. Konfigurasi Database (jika perlu)

Edit `db_connect.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // ganti dengan user MySQL Anda
define('DB_PASS', '');            // ganti dengan password MySQL Anda
define('DB_NAME', 'djaka_coffee');
```

---

## 🔐 Akun Login

| Role     | Username         | Password        |
|----------|-----------------|-----------------|
| Customer | Email Google valid | Password Google |
| Kasir    | `kasir`          | `kasirdjaka123` |
| Admin    | `admin`          | `admindjaka123` |

---

## 📋 Fitur per Halaman

### 🧑‍💼 Customer (pembeli.html)
- ✅ Navbar dengan logo, filter harga, booking meja, keranjang, pesanan
- ✅ Sidebar kategori (Makanan, Minuman, Dessert, Mix Plater)
- ✅ Grid menu dengan emoji, harga per ukuran (S/M/L)
- ✅ Pilih ukuran → tambah ke keranjang
- ✅ Counter qty real-time (1–99)
- ✅ Badge keranjang di navbar
- ✅ Price bar di bawah layar
- ✅ Keranjang drawer (kanan)
- ✅ Modal booking meja (10 meja)
- ✅ Modal pembayaran: Cash / QRIS (simulasi scan)
- ✅ Halaman pesanan + struk lengkap

### 💳 Kasir (kasir.html)
- ✅ Tab: Menu & Stok / Status Meja / Pesanan Masuk
- ✅ Manajemen stok per ukuran (tambah/kurang)
- ✅ Tombol restock semua (+10)
- ✅ Status meja real-time
- ✅ Tabel pesanan masuk
- ✅ Auto-refresh setiap 5 detik

### 🛠️ Admin (admin.html)
- ✅ Dashboard: revenue, pesanan, item terjual, chart menu terlaris
- ✅ Kelola menu: edit harga S/M/L
- ✅ Tabel semua pesanan + hapus
- ✅ Manajemen meja: bebaskan meja
- ✅ Daftar pengguna dari data pesanan
- ✅ Reset semua data

---

## 🗄️ Struktur Database MySQL

```sql
djaka_coffee
├── users         → Data pengguna (email, role)
├── menus         → Data menu (nama, kategori, harga S/M/L)
├── stock         → Stok per menu per ukuran
├── orders        → Data pesanan (order_num, user, total, metode)
├── order_items   → Item-item dalam pesanan
└── bookings      → Data pemesanan meja
```

---

## ⚙️ Mode Offline (tanpa PHP/MySQL)

Aplikasi sudah berfungsi penuh menggunakan **localStorage** browser tanpa perlu server PHP.  
Data disimpan di browser dan bersifat sementara (hilang jika clear browser data).

Untuk produksi dengan database permanen → gunakan PHP + MySQL seperti di atas.

---

## 📞 Kontak & Kustomisasi

Untuk kustomisasi logo, warna, atau menu nyata:
- Ganti emoji di `script.js` → objek `MENU_EMOJI`
- Ganti data menu di `Stock._default()` dalam `script.js`
- Tambah foto asli dengan mengganti `card-img-wrap` di `pembeli.html`

---

*© 2025 Djaka Coffee — All rights reserved*
