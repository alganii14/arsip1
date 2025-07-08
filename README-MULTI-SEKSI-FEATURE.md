# Fitur Multi-Seksi Arsip

## Deskripsi Fitur
Fitur ini memungkinkan setiap peminjam/seksi untuk:
1. **Memasukkan arsip mereka sendiri** - Setiap user bisa membuat dan mengelola arsip milik sendiri
2. **Melihat arsip yang mereka masukkan** - User bisa melihat, edit, dan delete arsip milik sendiri
3. **Meminjam arsip dari seksi lain** - Untuk melihat arsip dari seksi lain, user harus mengajukan peminjaman terlebih dahulu

## Perubahan yang Dilakukan

### 1. Database Schema
- **Migration**: `add_created_by_to_arsips_table.php`
  - Menambahkan kolom `created_by` ke tabel `arsips`
  - Menambahkan foreign key ke tabel `users`

### 2. Model Changes
- **Arsip Model**:
  - Menambahkan field `created_by` ke `$fillable`
  - Menambahkan relasi `creator()` ke User model
  - Menambahkan scope `ownedBy($userId)` untuk filter arsip milik user tertentu
  - Menambahkan scope `accessibleBy($userId)` untuk filter arsip yang bisa diakses user

### 3. Controller Logic
- **ArsipController**:
  - `index()`: Filter arsip berdasarkan role user dan ownership
  - `store()`: Otomatis set `created_by` saat membuat arsip baru
  - `edit()`: Authorization check - hanya pemilik/admin/petugas yang bisa edit
  - `destroy()`: Authorization check - hanya pemilik/admin/petugas yang bisa delete
  - `detail()`: Authorization check untuk melihat detail arsip

- **PeminjamanArsipController**:
  - `create()`: Filter arsip untuk peminjaman - peminjam hanya bisa pinjam arsip bukan milik sendiri

### 4. View Updates
- **arsip/index.blade.php**:
  - Menambahkan kolom "Dibuat oleh" di tabel
  - Menampilkan nama creator dan departemen
  - Badge "Milik Anda" untuk arsip milik sendiri
  - Logic aksi berbeda untuk pemilik vs non-pemilik arsip
  - Update tombol untuk peminjam: bisa edit/delete arsip sendiri, hanya bisa pinjam arsip orang lain

- **peminjaman/create.blade.php**:
  - Update teks help untuk menjelaskan bahwa peminjam hanya bisa pinjam arsip dari seksi lain

## Alur Kerja Fitur

### Untuk Role Admin/Petugas:
- Bisa melihat semua arsip
- Bisa membuat, edit, delete semua arsip
- Tidak ada batasan akses

### Untuk Role Peminjam:
1. **Arsip Milik Sendiri**:
   - Bisa melihat di daftar arsip
   - Bisa melihat detail
   - Bisa edit dan delete
   - Muncul badge "Milik Anda"

2. **Arsip Milik Seksi Lain**:
   - Bisa melihat di daftar arsip
   - Bisa melihat detail terbatas
   - Untuk akses penuh ke file, harus mengajukan peminjaman
   - Tidak bisa edit/delete
   - Tombol "Pinjam" tersedia

3. **Arsip yang Sedang Dipinjam**:
   - Bisa melihat dan download file selama masa peminjaman
   - Akses penuh selama status peminjaman aktif

## Authorization Rules

### Melihat Daftar Arsip:
- **Admin/Petugas**: Semua arsip aktif
- **Peminjam**: Arsip milik sendiri + arsip yang sedang dipinjam

### Melihat Detail Arsip:
- **Admin/Petugas**: Semua arsip
- **Peminjam**: Arsip milik sendiri + arsip yang sedang dipinjam dengan approval

### Edit/Delete Arsip:
- **Admin/Petugas**: Semua arsip
- **Peminjam**: Hanya arsip milik sendiri

### Download/View File:
- **Admin/Petugas**: Semua arsip
- **Peminjam**: Arsip milik sendiri + arsip yang dipinjam dengan approval

### Membuat Peminjaman:
- **Semua Role**: Bisa mengajukan peminjaman
- **Peminjam**: Tidak bisa meminjam arsip milik sendiri

## Installation

1. Jalankan migration:
```bash
php artisan migrate
```

2. Update data arsip lama (opsional):
```bash
php artisan db:seed --class=UpdateExistingArsipSeeder
```

## Keamanan

- Semua arsip yang dibuat akan otomatis ter-assign ke user yang membuatnya
- Authorization check di level controller mencegah akses tidak sah
- Peminjam tidak bisa mengakses arsip dari seksi lain tanpa proses peminjaman yang approved
- Foreign key constraint memastikan integritas data

## Benefit

1. **Ownership yang Jelas**: Setiap arsip memiliki pemilik yang jelas
2. **Kontrol Akses**: User hanya bisa mengelola arsip milik sendiri
3. **Transparansi**: Sistem peminjaman untuk akses antar seksi
4. **Audit Trail**: Bisa tracking siapa yang membuat arsip
5. **Multi-Tenant**: Mendukung multiple seksi dalam satu sistem
