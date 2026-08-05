## Deskripsi Perubahan

### Masalah yang Diselesaikan
https://github.com/OpenSID/KelolaDesa/issues/139
`OpenSID::loginOpenSID()` selalu gagal login ke server OpenSID Premium dengan error 500 Internal Server Error atau 404 Not Found.

### Perubahan yang Dilakukan

#### Bug Fixes (app/Libraries/OpenSID.php)
- **Endpoint login**: GET `siteman` → `index.php/siteman`, POST `index.php/siteman/auth` → `siteman/auth` (Laravel route menghapus `index.php`)
- **Captcha bypass**: Kirim `secret_code` = bcrypt hash dari kolom `password` user, bukan substring random
- **Encoding request**: `form_params` → `multipart` untuk handle karakter `$` di bcrypt hash
- **Redirect handling**: Hapus `'strict' => true` dari `allow_redirects` Guzzle
- **Null safety**: Cek user dulu sebelum akses `->password`
- **Cookie handling**: `'cookies' => true` → `new CookieJar()` eksplisit
- **Referer header**: Tambahkan `Referer` ke `$urlOpensid . '/index.php/siteman'`
- **Error handling**: Tambahkan `try-catch` + `Log::error()` (konsisten dengan codebase lain)
- **GuzzleException**: Tangkap `GuzzleException` secara terpisah untuk error HTTP
- **Success detection**: Cek `beranda` → cek `!siteman` di redirect history

#### Refactoring
- **Facade pattern**: Buat `App\Facades\OpenSID` — pemanggilan `OpenSID::loginOpenSID($password)` (static-like)
- **Service container**: Register binding `'opensid'` di `AppServiceProvider` (bind, bukan singleton — setiap request butuh instance baru)
- **Constructor injection dihapus**: Controller tidak perlu inject, cukup `use App\Facades\OpenSID`
- **OpensidTrait dihapus**: Dead code, tidak terpakai
- **Type hints & docblock**: Perbaiki dari `Date_conv` ke `OpenSID`

### Alasan Perubahan
Library `OpenSID` belum diperbarui setelah migrasi CodeIgniter → Laravel. Rute login berubah, captcha mechanism berubah, dan beberapa Guzzle option menyebabkan error.

---

## Tes yang Dilakukan

### Skenario Pengujian

1. Set `FTP_URL=http://192.168.1.3:8000` di `.env`
2. Panggil `OpenSID::loginOpenSID('password')` dari controller manapun (SuratController, TteController, LayananMandiriController)
3. Pastikan login berhasil dan return `Client` instance

### Unit Test
9 test cases, 14 assertions — menggunakan Guzzle MockHandler:
- Login berhasil dengan secret_code
- Login gagal server error (500)
- Login gagal CSRF token tidak ditemukan
- Login gagal user tidak ditemukan
- Login gagal redirect ke siteman
- setUser manual
- getClient/getCookieJar setelah login
- getBaseUrl
- Base URL trailing slash dihapus

### Bukti Pengujian
Log error sebelum perbaikan:
```
[2026-07-29 04:54:16] local.ERROR: Call to a member function getConfig() on string
[2026-07-29 05:02:14] local.ERROR: [OpenSID] Login gagal: Server error: POST http://192.168.1.3:8000/index.php/siteman/auth resulted in a 500 Internal Server Error
```

sudah bisa melkakukan download surat yang berkaitan dengan loginOpenSID
<img width="1710" height="1112" alt="image" src="https://github.com/user-attachments/assets/0b7da7e7-2146-49e6-ba43-29492835310f" />


---

## File yang Berubah

| File | Perubahan |
|------|-----------|
| `app/Libraries/OpenSID.php` | Bug fixes + refactoring ke instance method |
| `app/Facades/OpenSID.php` | **Baru** — Facade class |
| `config/app.php` | Tambah alias `OpenSID` |
| `app/Providers/AppServiceProvider.php` | Register binding `'opensid'` |
| `app/Http/Controllers/Admin/Surat/SuratController.php` | Pakai facade `OpenSID::loginOpenSID()` |
| `app/Http/Controllers/Admin/Surat/LayananMandiriController.php` | Sama |
| `app/Http/Controllers/Admin/Surat/TteController.php` | Sama |
| `app/Http/Traits/OpensidTrait.php` | **Dihapus** (dead code) |
| `tests/Unit/Libraries/OpenSIDTest.php` | **Baru** — 9 unit tests |

---

## Catatan Tambahan

- **secret_code** mekanisme: Premium membandingkan `secret_code` yang dikirim dengan bcrypt hash dari kolom `password` tabel `users` menggunakan strict comparison (`!==`), bukan `password_verify()`.
- Rute login (`siteman/auth`) dipindahkan dari CodeIgniter ke Laravel route di `donjo-app/Routes/Web/admin.php` yang menghapus `index.php` dari URL.
- **Bind vs Singleton**: Menggunakan `bind` (bukan `singleton`) karena `OpenSID::make()` membaca `auth('admin')->user()` — user berbeda per request.


---

## Checklist

- [x] Kode sudah diuji secara lokal
- [x] Tidak ada konflik merge
- [x] Semua test berhasil (9 tests, 14 assertions)
- [x] Screenshot dilampirkan jika ada 
