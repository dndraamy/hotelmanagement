# Enterprise Hotel Management System

## Overview

Enterprise Hotel Management System adalah solusi digital berbasis web untuk mengelola seluruh proses operasional hotel secara terintegrasi. Sistem ini menyatukan **10 modul utama** yang saling terhubung, sehingga seluruh aliran data antar departemen berjalan secara langsung tanpa silo informasi.

### Modul Sistem

| # | Modul | Pengguna Utama |
|---|-------|---------------|
| 1 | **Autentikasi & Otorisasi** | Semua pengguna |
| 2 | **Administrasi Personalia** | Staf HRD |
| 3 | **Manajemen Gudang** | Staf Gudang |
| 4 | **Manajemen Reservasi** | Resepsionis |
| 5 | **Layanan Resepsionis** | Resepsionis |
| 6 | **Manajemen Tata Graha** *(Housekeeping)* | Petugas Kebersihan |
| 7 | **Point of Sales (POS Restoran)** | Petugas Restoran |
| 8 | **Kas & Penagihan** | Staf Keuangan |
| 9 | **Waktu & Penggajian** | Karyawan, Staf HRD |
| 10 | **Pelaporan Eksekutif** | Manajer Hotel |

### Role Pengguna

Sistem menerapkan **Role-Based Access Control (RBAC)** dengan 8 role: `Resepsionis`, `Petugas Kebersihan`, `Staf Keuangan`, `Manajer Hotel`, `Staf HRD`, `Karyawan`, `Staf Gudang`, dan `Petugas Restoran`.

---

## Tech Stack

| Teknologi | Versi | Kegunaan | Dokumentasi |
|-----------|-------|----------|-------------|
| **PHP** | ^8.3 | Bahasa pemrograman server-side utama | [php.net/docs](https://www.php.net/docs.php) |
| **Laravel** | ^12.0 | Framework PHP utama untuk backend — routing, ORM, session, middleware, dll | [laravel.com/docs/12.x](https://laravel.com/docs/12.x) |
| **Laravel Breeze** | ^2.4 | Starter kit autentikasi (login, register, logout, session) | [laravel.com/docs/12.x/starter-kits](https://laravel.com/docs/12.x/starter-kits) |
| **Spatie Permission** | ^8.0 | Package untuk Role-Based Access Control (RBAC) — assign role, cek permission | [spatie.be/docs/laravel-permission](https://spatie.be/docs/laravel-permission/v6/introduction) |
| **MySQL** | ^8.0 | Database relasional untuk menyimpan seluruh data sistem | [dev.mysql.com/doc](https://dev.mysql.com/doc/) |
| **Blade** | (built-in) | Template engine Laravel untuk membuat halaman HTML | [laravel.com/docs/12.x/blade](https://laravel.com/docs/12.x/blade) |
| **Tailwind CSS** | ^3.1 | Utility-first CSS framework untuk styling UI | [tailwindcss.com/docs](https://tailwindcss.com/docs) |
| **Alpine.js** | ^3.4 | Framework JavaScript ringan untuk interaktivitas di halaman (dropdown, modal, toggle) | [alpinejs.dev](https://alpinejs.dev/start-here) |
| **Vite** | ^8.0 | Build tool & dev server untuk compile asset CSS/JS secara cepat | [vitejs.dev/guide](https://vitejs.dev/guide/) |
| **Pest** | ^4.7 | Framework testing PHP yang ekspresif, digunakan untuk unit & feature test | [pestphp.com/docs](https://pestphp.com/docs/installation) |
| **Composer** | ^2.x | Dependency manager untuk package PHP | [getcomposer.org/doc](https://getcomposer.org/doc/) |
| **Node.js + NPM** | ^20.x | Runtime JavaScript untuk menjalankan build tools (Vite, Tailwind) | [nodejs.org/docs](https://nodejs.org/en/docs) |

---

## Requirements

Pastikan semua software berikut sudah terinstall di komputer kamu **sebelum** memulai setup:

| Software | Versi Minimum | Cara Cek |
|----------|--------------|----------|
| PHP | **8.3** | `php -v` |
| Composer | **2.x** | `composer -V` |
| Node.js | **20.x** | `node -v` |
| NPM | **10.x** | `npm -v` |
| MySQL | **8.0** | `mysql --version` |
| Git | Terbaru | `git --version` |

### 💡 Rekomendasi: Gunakan Laragon (Windows)

Untuk mempermudah instalasi, sangat direkomendasikan menggunakan [**Laragon**](https://laragon.org/download/) yang sudah menyertakan PHP, MySQL, Composer, dan Node.js dalam satu paket.

---

## Setup / Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/dndraamy/hotelmanagement.git
cd hotelmanagement
```

### 2. Install Dependencies PHP

```bash
composer install
```

### 3. Konfigurasi Environment

```bash
# Salin file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

Buka file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_hotel_enterprise
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Buat Database

Buat database MySQL dengan nama `db_hotel_enterprise`:

```bash
# Via terminal MySQL
mysql -u root -e "CREATE DATABASE IF NOT EXISTS db_hotel_enterprise;"
```

Atau buat secara manual melalui **phpMyAdmin** / **HeidiSQL** / **MySQL Workbench**.

### 5. Jalankan Migrasi & Seeder

```bash
# Buat semua tabel
php artisan migrate

# Isi data awal (roles, user admin)
php artisan db:seed
```

> **Akun Super Admin default:**
> - Username: `superadmin`
> - Password: `password`

### 6. Install Dependencies Frontend

```bash
npm install
```

### 7. Jalankan Aplikasi

```bash
# Jalankan semuanya sekaligus (server + vite + queue + logs)
composer dev
```

Atau jalankan secara terpisah di terminal yang berbeda:

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server (untuk hot-reload CSS/JS)
npm run dev
```

Buka browser dan akses: **http://localhost:8000**

---

## Struktur Folder Penting

```
hotelmanagement/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Controller — logic setiap halaman/fitur
│   │   │   └── Auth/        # Controller bawaan Breeze (login, register)
│   │   ├── Middleware/       # Middleware — filter request (auth, role check)
│   │   └── Requests/        # Form Request — validasi input form
│   ├── Models/              # Eloquent Model — representasi tabel database
│   ├── Providers/           # Service Provider — konfigurasi binding service
│   └── View/                # View Component — komponen blade reusable
│
├── config/
│   ├── permission.php       # Konfigurasi Spatie Permission (RBAC)
│   └── ...                  # Konfigurasi Laravel lainnya
│
├── database/
│   ├── factories/           # Factory — generate data dummy untuk testing
│   ├── migrations/          # Migration — definisi skema tabel database
│   └── seeders/             # Seeder — data awal (roles, admin user)
│
├── resources/
│   ├── css/app.css          # File CSS utama (Tailwind)
│   ├── js/app.js            # File JS utama (Alpine.js)
│   └── views/               # Blade template — semua halaman UI
│       ├── layouts/         # Layout utama (header, sidebar, footer)
│       ├── components/      # Komponen blade reusable (button, card, modal)
│       └── auth/            # Halaman auth bawaan Breeze
│
├── routes/
│   ├── web.php              # Route utama — definisi URL dan controller
│   └── auth.php             # Route autentikasi bawaan Breeze
│
├── storage/
│   ├── app/public/          # File upload user (bukti nota, dll)
│   └── logs/                # Log file aplikasi
│
├── tests/
│   ├── Feature/             # Feature test — test alur lengkap fitur
│   └── Unit/                # Unit test — test fungsi individu
│
├── .env.example             # Template environment (JANGAN edit .env langsung)
├── composer.json            # Daftar dependencies PHP
├── package.json             # Daftar dependencies JS/CSS
├── tailwind.config.js       # Konfigurasi Tailwind CSS
└── vite.config.js           # Konfigurasi Vite build tool
```

### Di Mana Harus Coding?

| Kamu mengerjakan    |           Folder yang disentuh              |
|---------------------|---------------------------------------------|
| Halaman baru / UI   |`resources/views/`, `routes/web.php`         |
| Logic fitur / API   | `app/Http/Controllers/`                     |
| Validasi form       | `app/Http/Requests/`                        |
| Relasi antar tabel  | `app/Models/`                               |
| Skema tabel baru    | `database/migrations/`                      |
| Data dummy / testing| `database/factories/`, `database/seeders/`  |
| Styling halaman     | `resources/css/`, Tailwind classes di Blade |
| Interaksi JS ringan | Alpine.js langsung di Blade template        |
| Unit/Feature test   | `tests/Unit/`, `tests/Feature/`             |

---

## Perintah yang Sering Dipakai

```bash
# Jalankan aplikasi (server + vite + queue + logs)
composer dev

# Jalankan migrasi database
php artisan migrate

# Rollback migrasi terakhir
php artisan migrate:rollback

# Reset & re-migrate semua tabel + seed
php artisan migrate:fresh --seed

# Buat controller baru
php artisan make:controller NamaController

# Buat model baru (dengan migration, factory, seeder)
php artisan make:model NamaModel -mfs

# Buat form request baru
php artisan make:request NamaRequest

# Jalankan semua test
php artisan test

# Jalankan test spesifik
php artisan test --filter=NamaTest

# Clear semua cache
php artisan optimize:clear
```

---

## FAQ (Frequently Asked Questions)

### 1. ❌ `SQLSTATE[HY000] [1049] Unknown database 'db_hotel_enterprise'`

**Penyebab:** Database belum dibuat.

**Solusi:**
```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS db_hotel_enterprise;"
```
Atau buat manual via phpMyAdmin.

---

### 2. ❌ `SQLSTATE[HY000] [2002] Connection refused`

**Penyebab:** MySQL server belum jalan.

**Solusi:** Pastikan MySQL sudah berjalan di Laragon / XAMPP. Cek di Laragon panel → klik **Start All**.

---

### 3. ❌ `Vite manifest not found at: .../public/build/manifest.json`

**Penyebab:** Asset frontend belum di-build.

**Solusi:**
```bash
# Pastikan npm install sudah dijalankan
npm install

# Lalu jalankan vite
npm run dev
```

> ⚠️ Pastikan `npm run dev` tetap berjalan di terminal terpisah selama development. Atau gunakan `composer dev` yang menjalankan semuanya sekaligus.

---

### 4. ❌ `The stream or file ".../laravel.log" could not be opened`

**Penyebab:** Folder `storage` tidak memiliki permission yang cukup.

**Solusi:**
```bash
# Windows (buka CMD sebagai Administrator)
icacls storage /grant Everyone:F /T

# Atau di Linux/Mac
chmod -R 775 storage bootstrap/cache
```

---

### 5. ❌ `Class "Spatie\Permission\Models\Role" not found`

**Penyebab:** Cache config belum di-clear setelah install package.

**Solusi:**
```bash
php artisan optimize:clear
composer dump-autoload
```

---

### 6. ❌ `Your requirements could not be resolved to an installable set of packages`

**Penyebab:** Versi PHP tidak sesuai. Project ini membutuhkan PHP >= 8.3.

**Solusi:**
```bash
php -v   # Cek versi PHP
```
Jika kurang dari 8.3, update PHP di Laragon: **Menu → PHP → pilih versi 8.3+**.

---

### 7. ❌ `npm ERR! could not determine executable to run`

**Penyebab:** Node.js belum terinstall atau versi terlalu lama.

**Solusi:**
```bash
node -v   # Cek versi, harus >= 20.x
```
Download versi terbaru di [nodejs.org](https://nodejs.org/).

---

### 8. ❌ Error saat `php artisan migrate` — tabel sudah ada

**Penyebab:** Migrasi sudah pernah dijalankan sebelumnya.

**Solusi:**
```bash
# Reset semua tabel dan jalankan ulang migrasi + seeder
php artisan migrate:fresh --seed
```

> ⚠️ **Hati-hati!** Perintah ini akan **menghapus semua data** di database. Hanya gunakan di environment development.

---

### 9. ❓ Bagaimana cara cek role user di controller?

```php
// Cek apakah user punya role tertentu
if (auth()->user()->hasRole('Resepsionis')) {
    // akses fitur resepsionis
}

// Middleware di route
Route::middleware(['role:Staf HRD'])->group(function () {
    Route::get('/pegawai', [PegawaiController::class, 'index']);
});
```

---

### 10. ❓ Bagaimana cara assign role ke user baru?

```php
use App\Models\User;

$user = User::find(1);
$user->assignRole('Resepsionis');

// Atau saat create user baru
$newUser = User::create([...]);
$newUser->assignRole('Staf Gudang');
```

---

## Tim Pengembang

**RBPL SI-A** — Enterprise Hotel Management System  
Metodologi: **Scrum Framework**  
Total Sprint: **11 Sprint** | Durasi per Sprint: **5 Hari**
