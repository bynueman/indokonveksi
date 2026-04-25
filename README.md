# 🧵 Indokonveksi — Order & Sales Management System

**Indokonveksi** adalah sistem manajemen pesanan dan laporan penjualan berbasis Laravel dan Filament yang dirancang untuk membantu usaha konveksi dalam mengelola operasional secara digital dan terintegrasi.

Sistem ini menggantikan pencatatan manual menjadi sistem otomatis yang lebih efisien, akurat, dan mudah digunakan.

---

## 🚀 Overview

Indokonveksi membantu admin dalam:

* 📦 Mencatat pesanan pelanggan
* 👤 Mengelola data pelanggan
* 🧾 Mengelola transaksi penjualan
* 📊 Membuat laporan penjualan berdasarkan periode
* 🔄 Monitoring operasional konveksi secara real-time

---

## ✨ Key Features

* 🧵 Order management system
* 👥 Customer management
* 🧾 Sales & transaction tracking
* 📊 Laporan penjualan (harian, bulanan, custom)
* 🔎 Filter & pencarian data
* 🔐 Authentication & admin panel (Filament)
* ⚡ UI admin cepat dan modern

---

## 🛠️ Tech Stack

* Laravel
* Filament
* PHP
* MySQL
* Tailwind CSS
* Vite

---

## 📂 Core Modules

### 1. Order Management

* Input pesanan pelanggan
* Detail pesanan (jenis produk, jumlah, ukuran)
* Status pesanan (pending, proses, selesai)

### 2. Customer Management

* Data pelanggan
* Riwayat transaksi

### 3. Sales Tracking

* Pencatatan transaksi
* Total penjualan

### 4. Reporting System

* Laporan penjualan
* Filter berdasarkan:

  * Tanggal
  * Bulan
  * Periode tertentu

---

## ⚙️ Installation

Clone repository:

```bash
git clone https://github.com/byneuman/indokonveksi.git
cd indokonveksi
```

Install dependencies:

```bash
composer install
npm install
```

Setup environment:

```bash
cp .env.example .env
php artisan key:generate
```

Setup database:

```bash
php artisan migrate
```

Run project:

```bash
php artisan serve
npm run dev
```

---

## 🔐 Admin Panel (Filament)

Akses panel admin:

```
http://localhost:8000/admin
```

Buat user admin:

```bash
php artisan make:filament-user
```

---

## 📊 Workflow System

1. Admin menerima pesanan pelanggan
2. Input pesanan ke sistem
3. Data tersimpan otomatis
4. Transaksi tercatat
5. Laporan penjualan dapat di-generate kapan saja

---

## 🎯 Purpose

Sistem ini dibuat untuk:

* Menggantikan pencatatan manual
* Mengurangi kesalahan input data
* Mempercepat proses administrasi
* Mempermudah pembuatan laporan
* Meningkatkan efisiensi operasional konveksi

---

## 📌 Roadmap

* [ ] Dashboard grafik penjualan
* [ ] Export laporan (PDF / Excel)
* [ ] Manajemen stok bahan
* [ ] Tracking produksi (cutting, sewing, finishing)
* [ ] Multi-user role (admin/operator)
* [ ] Notifikasi pesanan

---

## 🔐 Security

* Laravel authentication
* Filament authorization
* CSRF protection
* Secure database handling

---

## 🤝 Contributing

Kontribusi terbuka untuk pengembangan lebih lanjut.

---

## 📄 License

MIT License

---

## 👨‍💻 Author

**By Nueman**
GitHub: https://github.com/bynueman
