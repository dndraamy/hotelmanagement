# Dokumentasi PBI-16: Fitur Tambah Data Pegawai

Dokumentasi ini menjelaskan mengenai implementasi fitur penambahan data pegawai baru ke dalam sistem manajemen hotel. Fitur ini dirancang khusus untuk memenuhi kebutuhan manajemen staf dengan tetap mempertahankan struktur kode yang bersih dan tidak merusak fitur yang sudah ada.

---

## 1. Ringkasan File & Perubahan

### Rute Baru (`routes/web.php`)
Dua rute baru telah dideklarasikan di dalam grup middleware `auth`:
*   `GET /pegawai/create` (Nama rute: `pegawai.create`): Menampilkan halaman formulir tambah pegawai.
*   `POST /pegawai` (Nama rute: `pegawai.store`): Mengirimkan data input ke controller untuk divalidasi dan disimpan.

### Controller Baru (`app/Http/Controllers/PegawaiController.php`)
Controller ini hanya memiliki dua metode utama untuk efisiensi dan kebersihan kode:
*   `create()`: Mengambil daftar `Divisi` dan `Jabatan` untuk ditampilkan sebagai opsi pilihan pada formulir dropdown.
*   `store(Request $request)`: Melakukan validasi input data pegawai, membuat record pegawai baru di database, dan mengarahkan kembali pengguna dengan pesan sukses.
    *   **Validasi yang diterapkan**:
        *   `nama_lengkap`: Wajib diisi, string, maksimal 255 karakter.
        *   `kontak`: Wajib diisi, string, maksimal 20 karakter.
        *   `alamat`: Wajib diisi, string.
        *   `id_divisi`: Wajib diisi, harus valid (ada di database).
        *   `id_jabatan`: Wajib diisi, harus valid (ada di database).

### View Baru (`resources/views/pegawai/create.blade.php`)
Tampilan antarmuka pengguna (UI) dibuat agar selaras dengan desain modul lainnya (Tailwind CSS, font Montserrat, skema warna gelap & emas hotel).

---

## 2. Fitur Debugging (Console Log)

Untuk memudahkan proses pengujian dan pelacakan (debugging), sistem telah dilengkapi dengan penangkap sesi sukses yang langsung mencetak pesan ke konsol browser.

### Kode Debugging (`create.blade.php`):
```html
@if(session('success'))
    <script>
        console.log("Success: {{ session('success') }}");
    </script>
@endif
```

### Cara Verifikasi Debugging:
1. Buka halaman tambah pegawai di browser.
2. Buka **Developer Tools** (Tekan tombol **F12** atau Klik Kanan -> **Inspect**).
3. Pindah ke tab **Console**.
4. Isi formulir pegawai dan klik **Simpan Pegawai**.
5. Jika berhasil disimpan, konsol browser akan langsung menampilkan pesan:
   `Success: Data pegawai berhasil ditambahkan.`

---

## 3. Petunjuk Pengujian dan Menjalankan Proyek

### Kebutuhan Sistem
Proyek ini dikonfigurasi menggunakan dependensi modern (Laravel 12 & Pest v4) yang membutuhkan **PHP versi 8.3 atau 8.4**.

### Langkah-langkah Menjalankan:
1.  **Migrasi dan Seed Database** (Jika belum dilakukan):
    ```powershell
    # Menjalankan migrasi database
    & "C:\PHP\php.exe" artisan migrate

    # Memasukkan data awal (divisi, jabatan, & akun admin demo)
    & "C:\PHP\php.exe" artisan db:seed
    ```
2.  **Jalankan Server Laravel & Frontend**:
    ```powershell
    # Memaksa sesi terminal menggunakan PHP 8.4 sementara
    $env:PATH = "C:\PHP;" + $env:PATH

    # Jalankan server local (menjalankan server, queue, dan Vite secara bersamaan)
    composer run dev
    ```
3.  **Akses Halaman**:
    - Silakan login terlebih dahulu melalui `http://localhost:8000/login` menggunakan akun demo:
      - **Email**: `admin@hotel.test`
      - **Password**: `password`
    - Buka halaman tambah pegawai secara langsung melalui URL: `http://localhost:8000/pegawai/create`
