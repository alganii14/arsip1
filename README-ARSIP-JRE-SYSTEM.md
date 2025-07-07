# Sistem Pengelolaan Arsip dengan JRE (Jadwal Retensi Elektronik)

## Penjelasan Sistem

Sistem ini mengelola arsip dengan masa retensi yang otomatis memindahkan arsip ke JRE (Jadwal Retensi Elektronik) ketika masa retensi habis, dan dapat memulihkan arsip kembali ke daftar aktif.

## Cara Kerja

### 1. Arsip Aktif → JRE
- Ketika arsip mencapai masa retensi (`retention_date` <= tanggal hari ini)
- Arsip otomatis dipindahkan ke tabel JRE
- Status `is_archived_to_jre` diubah menjadi `true`
- Arsip **TIDAK LAGI MUNCUL** di daftar arsip aktif
- Arsip hanya bisa dilihat di daftar JRE

### 2. JRE → Arsip Aktif (Recovery)
- Arsip di JRE dapat dipulihkan kembali
- Status `is_archived_to_jre` diubah menjadi `false`
- Arsip **KEMBALI MUNCUL** di daftar arsip aktif
- **JRE record DIHAPUS** setelah recovery (tidak ada jejak JRE)

## Fitur Utama

### Model Arsip
- **Scope `active()`**: Hanya menampilkan arsip yang belum diarsipkan ke JRE
- **Scope `archivedToJre()`**: Hanya menampilkan arsip yang sudah diarsipkan ke JRE
- **Method `moveToJre()`**: Memindahkan arsip ke JRE

### Model JRE
- **Method `recoverToArsip()`**: Memulihkan arsip dengan masa retensi default
- **Method `recoverWithCustomYears()`**: Memulihkan arsip dengan masa retensi custom

### Controller
- **ArsipController**: Hanya menampilkan arsip aktif (`Arsip::active()`)
- **JreController**: Mengelola arsip di JRE dan proses recovery

## Commands

### 1. Cek Retensi Manual
```bash
php artisan arsip:check-retention
```

### 2. Pindahkan Arsip Expired Otomatis
```bash
php artisan arsip:auto-move-expired
```

### 3. Bersihkan JRE Records yang Sudah Recovered
```bash
php artisan jre:cleanup-recovered
```

## Cara Penggunaan

### 1. Melihat Arsip Aktif
- Buka halaman Arsip
- Hanya arsip yang belum diarsipkan ke JRE yang akan muncul

### 2. Melihat Arsip di JRE
- Buka halaman JRE
- Semua arsip yang sudah diarsipkan akan muncul di sini

### 3. Memulihkan Arsip dari JRE
- Pilih arsip di daftar JRE
- Klik tombol "Pulihkan" atau "Pulihkan dengan Tahun Custom"
- Arsip akan kembali ke daftar aktif

### 4. Otomatis Proses (Recommended)
- Jadwalkan command `php artisan arsip:auto-move-expired` di cron job
- Jalankan setiap hari untuk memindahkan arsip expired otomatis

## Contoh Cron Job
```bash
# Jalankan setiap hari jam 1 pagi
0 1 * * * cd /path/to/your/project && php artisan arsip:auto-move-expired
```

## Status JRE

1. **inactive**: Arsip baru dipindahkan ke JRE, belum diproses
2. **destroyed**: Arsip telah dimusnahkan
3. **transferred**: Arsip dipindahkan ke lokasi lain

**Catatan**: Status `recovered` tidak lagi digunakan karena JRE record langsung dihapus setelah recovery.

## Validasi Sistem

Jalankan demo untuk memverifikasi sistem:
```bash
php demo_arsip_jre_system.php
```

## Keamanan Data

- Data arsip tetap aman tersimpan di database
- Sistem hanya mengubah status visibility
- Tidak ada data yang dihapus secara permanen
- Proses recovery dapat dilakukan kapan saja

## Benefit

1. **Otomatis**: Arsip expired otomatis dipindahkan
2. **Reversible**: Arsip dapat dipulihkan kapan saja
3. **Clean Interface**: Daftar arsip aktif tidak tercampur dengan arsip expired
4. **No Residual Data**: JRE record dihapus setelah recovery, tidak ada jejak ganda
5. **True Migration**: Arsip benar-benar "pindah" antara tabel
6. **Flexible**: Masa retensi dapat disesuaikan saat recovery
