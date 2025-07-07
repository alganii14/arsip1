# Fitur Pemulihan JRE dengan Pilihan Manual

## Deskripsi
Fitur ini memungkinkan pengguna untuk memilih masa retensi secara manual ketika memulihkan arsip dari JRE (Jadwal Retensi Elektronik). Sebelumnya, sistem hanya menggunakan masa retensi default.

## Fitur Utama

### 1. Pemulihan dengan Masa Retensi Manual
- Pengguna dapat memilih masa retensi dari 1 tahun hingga 30 tahun
- Tersedia opsi "Permanen" untuk arsip yang tidak memiliki batas waktu
- Preview tanggal retensi ditampilkan secara real-time

### 2. Pilihan Pemulihan Ganda
- **Pulihkan (Default)**: Menggunakan masa retensi default atau yang sudah tersimpan
- **Pilih Tahun**: Membuka modal untuk memilih masa retensi custom

### 3. Tracking Masa Pemulihan
- Masa retensi yang dipilih disimpan di database
- Ditampilkan di halaman index dan detail JRE
- Catatan JRE dikosongkan saat pemulihan (tidak ada catatan otomatis)

## Perubahan Database

### Tabel `jres`
Ditambahkan kolom baru:
- `recovery_years` (integer, nullable): Menyimpan masa pemulihan yang dipilih manual

## Perubahan File

### 1. Migration
- `database/migrations/2025_07_07_072351_add_recovery_years_to_jres_table.php`

### 2. Model
- `app/Models/Jre.php`: Ditambahkan `recovery_years` ke `$fillable`

### 3. Controller
- `app/Http/Controllers/JreController.php`:
  - Method `recover()`: Diperbarui untuk menggunakan recovery_years
  - Method `recoverWithYears()`: Method baru untuk recovery dengan pilihan manual

### 4. Routes
- `routes/web.php`: Ditambahkan route `jre.recover-with-years`

### 5. Views
- `resources/views/jre/edit.blade.php`: Ditambahkan UI untuk pemilihan manual
- `resources/views/jre/index.blade.php`: Ditambahkan kolom masa pemulihan
- `resources/views/jre/show.blade.php`: Ditambahkan informasi masa pemulihan

## Cara Penggunaan

### 1. Pemulihan Default
1. Buka halaman Edit JRE
2. Klik tombol "Pulihkan (Default)"
3. Konfirmasi pemulihan

### 2. Pemulihan dengan Pilihan Manual
1. Buka halaman Edit JRE
2. Klik tombol "Pilih Tahun"
3. Pilih masa retensi yang diinginkan dari dropdown
4. Lihat preview tanggal retensi
5. Klik "Pulihkan Arsip"
6. Konfirmasi pemulihan

## Opsi Masa Retensi

| Pilihan | Deskripsi |
|---------|-----------|
| 1 Tahun | Arsip akan masuk JRE dalam 1 tahun |
| 2 Tahun | Arsip akan masuk JRE dalam 2 tahun |
| 3 Tahun | Arsip akan masuk JRE dalam 3 tahun |
| 5 Tahun | Default, arsip akan masuk JRE dalam 5 tahun |
| 7 Tahun | Arsip akan masuk JRE dalam 7 tahun |
| 10 Tahun | Arsip akan masuk JRE dalam 10 tahun |
| 15 Tahun | Arsip akan masuk JRE dalam 15 tahun |
| 20 Tahun | Arsip akan masuk JRE dalam 20 tahun |
| 25 Tahun | Arsip akan masuk JRE dalam 25 tahun |
| 30 Tahun | Arsip akan masuk JRE dalam 30 tahun |
| Permanen | Arsip tidak akan masuk JRE lagi |

## Catatan Teknis

### Masa Retensi Permanen
- Nilai "Permanen" disimpan sebagai 999 tahun di database
- Tanggal retensi diset 100 tahun ke depan (praktis permanen)
- Ditampilkan dengan icon infinity di interface

### Perhitungan Tanggal
- Semua perhitungan tanggal retensi dimulai dari tanggal hari ini
- Menggunakan Carbon library untuk manipulasi tanggal
- Tanggal ditampilkan dalam format Indonesia (dd/mm/yyyy)

### Keamanan
- Validasi input pada semua form
- Konfirmasi sebelum melakukan pemulihan
- Catatan JRE dikosongkan saat pemulihan untuk memberikan fleksibilitas penuh kepada user

## Testing
Gunakan script `test_jre_recovery_feature.php` untuk memverifikasi semua fitur berfungsi dengan baik.

## Kompatibilitas
- Laravel 10.x
- PHP 8.x
- MySQL 5.7+
- Bootstrap 5.x
- Font Awesome 5.x
