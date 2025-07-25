# Aplikasi Absensi Sekolah (QR Code & Geolokasi)

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20.svg?style=for-the-badge" alt="Laravel 12">
  <a href="https://opensource.org/licenses/MIT">
    <img src="https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge" alt="License">
  </a>
</p>

---

## ✨ Tentang Proyek

Aplikasi Absensi Sekolah adalah sistem manajemen kehadiran berbasis web yang dibangun dengan **Laravel 12**. Sistem ini memanfaatkan **QR Code** dan **Geolokasi** untuk memastikan kehadiran yang sah dan akurat.

Sistem memiliki tiga hak akses:
- **Admin**
- **Guru**
- **Siswa**

Masing-masing dengan fungsionalitas dashboard yang berbeda.

---

## 🚀 Fitur Utama

- **Kontrol Akses Berbasis Peran (Role):**
  - **Admin:** Mengelola data master, statistik, kontrol penuh sistem.
  - **Guru:** Mengelola absensi, jadwal, validasi izin.
  - **Siswa:** Melihat jadwal & riwayat absensi.

- **Dashboard Informatif:**
  - Statistik jumlah siswa, guru, kelas.
  - Grafik absensi harian (hadir, sakit, izin, alpa).

- **Manajemen Data Master (CRUD):**
  - Siswa (akun login otomatis)
  - Guru (akun login otomatis)
  - Kelas (dengan wali kelas)
  - Mata Pelajaran
  - Tahun Ajaran

- **Manajemen Jadwal & Absensi:**
  - Jadwal pelajaran per kelas
  - Absensi per jam pelajaran
  - Pengajuan izin oleh siswa

- **Validasi Kehadiran:**
  - **Geolokasi:** Validasi radius lokasi sekolah
  - **QR Code:** Scan kode unik di kelas

---

## 🔧 Teknologi yang Digunakan

- **Backend:** Laravel 12, PHP 8.3+
- **Frontend:** Blade, Bootstrap 5, ApexCharts.js
- **Database:** MySQL / MariaDB

---

## ⚙️ Panduan Instalasi Lokal

1. **Clone Repositori**
   ```bash
   git clone https://github.com/username/absensi-sekolah-qrcode-geolokasi.git
   cd absensi-sekolah-qrcode-geolokasi
   ```

2. **Instal Dependensi**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   ```
   Lalu edit `.env` sesuai konfigurasi database kamu.

4. **Generate Kunci Aplikasi**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi & Seeding Database**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Compile Aset Frontend**
   ```bash
   npm run dev
   ```

7. **Jalankan Server Lokal**
   ```bash
   php artisan serve
   ```
   Akses di `http://127.0.0.1:8000`.

---

## 🔑 Akun Default (Seeder)

- **Admin**
  - Username: `admin`
  - Password: `password`

- **Guru**
  - Username: `budi`
  - Password: `password`

- **Siswa**
  - Username: `siswa101`
  - Password: `password`

---

## 🤝 Berkontribusi

Kontribusi berupa pull request, issue, atau ide fitur sangat kami apresiasi.

---

## 📄 Lisensi

Proyek ini dilindungi oleh [Lisensi MIT](https://opensource.org/licenses/MIT).

---

## 💡 Tips Tambahan

- Simpan file ini sebagai `README.md` di folder root proyek Laravel-mu.
- Jika diunggah ke GitHub, file ini akan otomatis ditampilkan di halaman depan repositori.