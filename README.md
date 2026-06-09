# 📦 Sobat Kurir

![Laravel](https://img.shields.io/badge/Laravel-Project-red?style=flat-square\&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-Backend-blue?style=flat-square\&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-Database-orange?style=flat-square\&logo=mysql)
![Status](https://img.shields.io/badge/Status-Development-green?style=flat-square)

**Sobat Kurir** adalah aplikasi pengiriman paket berbasis web yang dibuat untuk membantu proses pemesanan, pengecekan ongkir, pelacakan paket, dan pengelolaan data pengiriman.

Aplikasi ini dikembangkan menggunakan **Laravel** sebagai framework utama dan **MySQL/MariaDB** sebagai database.

---

## ✨ Fitur Utama

* Login dan register pengguna
* Pengelolaan data admin, customer, dan kurir
* Pemesanan pengiriman paket
* Pengecekan ongkir
* Pembuatan nomor resi otomatis
* Pelacakan status paket
* Riwayat pesanan
* Pengelolaan status pengiriman

---

## 🛠️ Teknologi yang Digunakan

| Teknologi       | Keterangan                     |
| --------------- | ------------------------------ |
| Laravel         | Framework backend utama        |
| PHP             | Bahasa pemrograman server-side |
| MySQL / MariaDB | Database aplikasi              |
| Blade           | Template tampilan Laravel      |
| Bootstrap / CSS | Styling tampilan               |
| Laragon         | Local server development       |
| GitHub          | Penyimpanan source code        |

---

## 📁 Struktur Project

```txt
sobat_kurir/
├── app/              # Logic utama aplikasi
├── database/         # Migration dan pengaturan database
├── public/           # Asset publik seperti gambar, CSS, dan JavaScript
├── resources/        # File tampilan halaman
├── routes/           # Pengaturan route aplikasi
├── .env.example      # Contoh konfigurasi environment
└── README.md         # Dokumentasi project
```

---

## ⚙️ Kebutuhan Sistem

Sebelum menjalankan project, pastikan sudah menginstall:

* PHP
* Composer
* MySQL / MariaDB
* Laragon atau XAMPP
* Browser
* Git

---

## 🚀 Cara Instalasi

Clone repository:

```bash
git clone https://github.com/davinaznaf/sobat_kurir.git
```

Masuk ke folder project:

```bash
cd sobat_kurir
```

Install dependency Laravel:

```bash
composer install
```

Salin file `.env.example` menjadi `.env`:

```bash
copy .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

---

## 🗄️ Pengaturan Database

Buat database baru di phpMyAdmin dengan nama:

```txt
db_sobat_kurir
```

Lalu sesuaikan konfigurasi database pada file `.env`:

```env
DB_DATABASE=db_sobat_kurir
DB_USERNAME=root
DB_PASSWORD=
```

Setelah itu jalankan migrasi:

```bash
php artisan migrate
```

Jika project menggunakan file SQL, import file database melalui phpMyAdmin.

---

## ▶️ Menjalankan Project

Jalankan server Laravel:

```bash
php artisan serve
```

Buka project di browser:

```txt
http://127.0.0.1:8000
```

---

## 👤 Role Pengguna

Aplikasi ini memiliki 5 role pengguna, yaitu:

| Role       | Keterangan                                                       |
| ---------- | ---------------------------------------------------------------- |
| Owner      | Memantau keseluruhan data dan perkembangan sistem                |
| Supervisor | Mengawasi proses operasional dan kinerja pengguna                |
| Admin      | Mengelola data utama, pesanan, dan kebutuhan administrasi sistem |
| Kurir      | Mengelola proses pengiriman dan memperbarui status paket         |
| Customer   | Membuat pesanan, mengecek ongkir, dan melacak paket              |

---

## 📌 Catatan

Project ini dibuat sebagai aplikasi pengiriman paket sederhana untuk kebutuhan pembelajaran dan pengembangan sistem berbasis Laravel.

---

## 📄 Lisensi

Project ini digunakan untuk kebutuhan pembelajaran.
