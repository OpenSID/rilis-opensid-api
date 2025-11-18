# OpenSID API (Premium)

Komponen API resmi untuk ekosistem OpenSID yang disediakan khusus bagi Pelanggan Premium. Repo ini berisi layanan backend berbasis Laravel yang menyediakan endpoint untuk fitur-fitur OpenSID seperti autentikasi, layanan mandiri warga, artikel/berita, dokumen, pengelolaan surat, notifikasi, kehadiran perangkat desa, statistik, dan pengaduan.

Catatan: Repositori ini bukan perangkat lunak sumber terbuka yang bebas digunakan. Hak akses dan penggunaan tunduk pada lisensi Pelanggan Premium OpenSID. Lihat berkas `LICENSE` untuk detailnya.

## Ringkas Fitur

- Autentikasi: login/logout, verifikasi email, reset PIN/kata sandi (JWT)
- Profil/Identitas Desa dan konfigurasi
- Artikel, kategori, komentar, agenda desa
- Layanan Mandiri: pesan, cetak biodata/KK, program bantuan, dokumen warga, permohonan surat
- Notifikasi (FCM) untuk warga dan admin
- Kehadiran perangkat desa (lapor hadir/keluar, konfigurasi, cek libur)
- Pengaduan dan pelapak/lapak desa
- Administrasi: arsip surat, TTE, validasi token, statistik

Struktur rute utama dapat dilihat di `routes/api/apiv1.php` dan `routes/api/admin.php`.

## Persyaratan Sistem

- PHP 8.1 atau lebih baru
- Composer
- Database MySQL/MariaDB
- Ekstensi/intalasi PHP yang sesuai untuk Laravel 10

Dependensi inti (lihat `composer.json`): Laravel 10, Sanctum, JWT Auth, Guzzle, Fractal, Query Builder, DomPDF/HTML2PDF, FCM, dsb.

## Instalasi (Pelanggan Premium)

1. Dapatkan akses repo/paket melalui kanal resmi Pelanggan Premium OpenSID.
2. Clone repo ini ke server Anda.
3. Salin konfigurasi lingkungan:
	- Salin `.env.example` menjadi `.env` (composer sudah menyiapkan skrip otomatis untuk ini).
4. Pasang dependensi:
	```bash
	composer install
	```
5. Generate kunci aplikasi dan inisialisasi JWT:
	```bash
	php artisan key:generate
	php artisan jwt:secret
	```
6. Konfigurasi `.env` sesuai lingkungan Anda (DB, mail, queue, storage, FCM, dsb.).
7. Jalankan migrasi/seed bila diperlukan:
	```bash
	php artisan migrate --force
	```
8. Publikasi aset/konfigurasi yang diperlukan (beberapa dijalankan otomatis oleh composer script):
	```bash
	php artisan vendor:publish --tag=laravel-assets --force
	php artisan gabungan:install
	```
9. Opsional: buat symlink storage dan jalankan queue/cron yang dibutuhkan:
	```bash
	php artisan storage:link
	php artisan queue:work
	```

## Menjalankan Aplikasi

- Server bawaan (pengembangan):
  ```bash
  php artisan serve
  ```
- Aplikasi akan mengekspose endpoint API sesuai definisi di `routes/api/*`.

## Keamanan & Kredensial

- Gunakan `APP_KEY` dan `JWT_SECRET` yang unik untuk setiap lingkungan.
- Simpan kredensial FCM, mail, dan rahasia lainnya hanya di `.env`.
- Aktifkan HTTPS di lingkungan produksi.

## Dukungan

Dukungan teknis disediakan melalui kanal resmi Pelanggan Premium OpenSID. Harap siapkan informasi versi aplikasi, log error yang relevan (`storage/logs`), dan langkah reproduksi saat membuat tiket.

## Lisensi

Penggunaan perangkat lunak ini tunduk pada Lisensi Penggunaan API OpenSID (Premium). Lihat berkas `LICENSE`.
