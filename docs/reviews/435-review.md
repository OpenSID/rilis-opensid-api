# Pull Request: Fix: Nama surat null pada arsip surat menggunakan alias suratTemplate

## Description

PR ini memperbaiki masalah pada route `api/admin/surat/arsip` di mana nilai `formatSurat` selalu `null` sehingga `nama_surat` yang dikembalikan juga `null`. Solusi sementara menggunakan alias hubungan `suratTemplate` sebagai ganti `formatSurat`, dan menambah fallback di transformer untuk menggunakan `suratTemplate` ketika `formatSurat` bernilai null.

## Changes made:

1. **Model (`app/Models/LogSurat.php`)**: Menambahkan hubungan `suratTemplate()` sebagai duplikat dari `formatSurat()` dengan foreign key yang sama (`id_format_surat`)
2. **Repository (`app/Http/Repository/ArsipSuratEntity.php`)**: Mengubah `QueryBuilder::for(LogSurat::class)` menjadi `QueryBuilder::for(LogSurat::with(['suratTemplate']))` agar eager loading menggunakan alias baru
3. **Transformer (`app/Http/Transformers/SuratAdminTransformer.php`)**: Menambah fallback `?? $surat->suratTemplate?->nama` pada field `nama_surat` ketika `formatSurat` null
4. **Controller (`app/Http/Controllers/Admin/Surat/SuratController.php`)**: Menyimpan hasil `getAdmin()` ke variabel dan menambahkan logger debug untuk investigasi

## Reason for change:

- **Akar masalah**: Hubungan `formatSurat` pada model `LogSurat` yang di-*eager load* selalu mengembalikan `null` di lingkungan produksi pada route arsip, meskipun data terkait tersedia di database dan `id_format_surat` valid
- **Dampak**: Field `nama_surat` pada response API arsip selalu `null`, membuat pengguna admin tidak dapat melihat nama jenis surat di halaman arsip
- **Solusi sementara**: Membuat alias hubungan `suratTemplate` dan menggunakannya sebagai fallback agar data nama surat tetap tampil

## Impact of change:

✅ **Aspect 1**: `nama_surat` pada response arsip kini menampilkan nama jenis surat meskipun `formatSurat` bernilai null
✅ **Aspect 2**: Tidak merubah struktur database atau data yang ada

## Related Issue

Closes https://github.com/OpenSID/KelolaDesa/issues/171

## Steps to Reproduce

### Before fix (problem):
1. Akses endpoint `GET api/admin/surat/arsip`
2. Periksa response JSON untuk field `nama_surat`
3. ❌ `nama_surat` bernilai `null` meskipun data surat di database valid

### After fix (solution):
1. Akses endpoint `GET api/admin/surat/arsip`
2. Periksa response JSON untuk field `nama_surat`
3. ✅ `nama_surat` menampilkan nama jenis surat yang benar

## Testing

### Manual Testing
- [ ] Akses `api/admin/surat/arsip` dan verifikasi `nama_surat` tidak null
- [ ] Akses `api/admin/surat/arsiptolak` dan verifikasi `nama_surat` tidak null
- [ ] Cek bahwa endpoint `/show` tetap berfungsi normal (menggunakan `formatSurat` langsung)

### Automated Testing
- [ ] Unit test untuk `SuratAdminTransformer` memverifikasi fallback `suratTemplate`
- [ ] Test bahwa `LogSurat::suratTemplate()` mengembalikan model `FormatSurat` yang valid

## Technical Details

### Root Cause Analysis
- Eager loading `formatSurat` via `$with` pada `LogSurat` model ternyata mengembalikan `null` pada query yang menggunakan Spatie QueryBuilder dengan `select('*')` dan `selectRaw` di `getAdmin()`
- Query yang dimodifikasi oleh `selectRaw` (untuk kolom `verifikasi` dan `status_periksa`) mungkin mengganggu hubungan eager loading `formatSurat`
- Menggunakan alias `suratTemplate` dengan eager loading eksplisit di `ArsipSuratEntity::getAdmin()` menghindari konflik ini

### Catatan Penting
- Logging debug di `SuratController::arsip()` (`logger('info', ...)`) sebaiknya dihapus setelah masalah diverifikasi
- Duplikasi hubungan `suratTemplate` dan `formatSurat` menambah utang teknis; sebaiknya dicari akar penyebab mengapa `formatSurat` eager loading gagal

## Breaking Changes

None

## References
- Issue: https://github.com/OpenSID/KelolaDesa/issues/171

## Screenshots

<img width="1366" height="768" alt="image" src="https://github.com/user-attachments/assets/7a6dc8ff-a796-4d9d-91ef-01ca45d93661" />
