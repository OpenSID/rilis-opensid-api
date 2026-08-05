## Nama PR

[Perbaikan] Memperbaiki HOST_PREMIUM tidak terbaca dari .env dan healthcheck premium

---

## Deskripsi Perubahan

### Masalah yang Diselesaikan
#432 

- HOST_PREMIUM di-hardcode, perubahan di .env tidak berpengaruh
- Healthcheck premium return string error (truthy) saat gagal, sehingga healthcheck tetap "pass"

### Perubahan yang Dilakukan
- HandlePremiumMiddleware: tambah guard clause jika konfigurasi kosong (host, kode_desa, atau token tidak di-set)
- HandlePremiumMiddleware: ubah `$response->clientError()` ke `$response->failed()` untuk tangkap semua error (4xx + 5xx)
- HandlePremiumMiddleware: tambah logging saat premium check gagal untuk debugging
- HealthCheckController: ubah return premium healthcheck dari string error ke `false` agar healthcheck benar-benar gagal
- Tambah pengujian otomatis `tests/Feature/PremiumValidationTest.php` untuk menguji middleware dan callback healthcheck premium

### Alasan Perubahan
- Memastikan HOST_PREMIUM dibaca dari .env secara dinamis sesuai konfigurasi Laravel
- Healthcheck harus return boolean yang akurat, bukan truthy string

---

## Tes yang Dilakukan

### Skenario Pengujian

1. Set HOST_PREMIUM ke URL valid (`https://devlayanan.opendesa.id`)
2. Jalankan `php artisan optimize:clear`
3. Jalankan `php artisan tinker --execute="echo config('services.layanan.domain');"`
    - Expected: `https://devlayanan.opendesa.id` ✓
4. Test premium API call via tinker — request berhasil ke host yang benar
5. Set HOST_PREMIUM ke URL invalid (`https://contoh-tidak-ada-domain.test`)
6. Clear cache, test lagi — request gagal dengan `cURL error 28: timeout` ✓
7. Kembalikan ke URL valid — test berhasil lagi ✓
8. Hit healthcheck endpoint untuk memverifikasi premium healthcheck bekerja ✓
9. Jalankan automated test `vendor/bin/phpunit tests/Feature/PremiumValidationTest.php` — 5/5 test lulus ✓

### Bukti Pengujian

- Test 1 (URL valid): Status 401 "Token not provided" (config terbaca)
- Test 2 (URL invalid): cURL error 28 timeout (request ke host salah)
- Healthcheck setelah fix symlink:
    ```json
    {
        "premium": false,
        "database": true,
        "ftp": true,
        "email": true,
        "production": false,
        "appkey": true,
        "kodeDesa": "1307042001"
    }
    ```
- `premium: false` karena KODE_DESA belum terdaftar di server premium (server-side issue)

---

## Catatan Tambahan

- `premium: false` adalah expected behavior untuk KODE_DESA yang belum terdaftar di server premium. Perlu didaftarkan di sisi server `devlayanan.opendesa.id`.

---

## Checklist

- [x] Kode sudah diuji secara lokal
- [x] Tidak ada konflik merge
- [x] Semua test berhasil
- [ ] Dokumentasi diperbarui
- [x] Screenshot dilampirkan jika ada perubahan UI
