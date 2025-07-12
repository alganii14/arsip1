# Fitur Peminjaman Fisik dan Digital

## Deskripsi
Fitur ini memungkinkan pengguna untuk memilih jenis peminjaman arsip:

### 1. Peminjaman Fisik
- Pengguna harus mengisi form peminjaman lengkap
- Arsip fisik akan diserahkan setelah persetujuan
- Memerlukan proses persetujuan admin (untuk role peminjam)
- Data peminjaman tercatat dengan detail lengkap

### 2. Peminjaman Digital  
- Langsung meminjam tanpa mengisi form
- File arsip langsung dapat diunduh
- Otomatis approved (tidak perlu persetujuan admin)
- Batas waktu default 7 hari

## Cara Menggunakan

### Dari Halaman Peminjaman
1. Klik dropdown "Tambah Peminjaman"
2. Pilih "Pinjam Fisik (Isi Form)" atau "Pinjam Digital (Langsung)"

#### Pinjam Fisik:
- Akan redirect ke halaman form peminjaman
- Isi semua data yang diperlukan
- Submit form dan tunggu persetujuan admin

#### Pinjam Digital:
- Modal akan muncul dengan daftar arsip
- Gunakan fitur search untuk mencari arsip
- Klik "Pinjam Digital" pada arsip yang diinginkan
- File akan otomatis diunduh

## Fitur Tambahan

### Indikator Jenis Peminjaman
- Badge warna pada tabel peminjaman:
  - 🟢 **Hijau**: Digital
  - 🔵 **Biru**: Fisik  
  - ⚫ **Abu-abu**: Umum

### Validasi
- Peminjam tidak dapat meminjam arsip milik sendiri
- Arsip yang sedang dipinjam tidak dapat dipinjam lagi
- Validasi jenis peminjaman (fisik/digital/umum)

## Perubahan Database
- Ditambahkan kolom `jenis_peminjaman` pada tabel `peminjaman_arsips`
- Enum values: 'fisik', 'digital', 'umum'
- Default value: 'umum'

## Route Baru
- `POST /peminjaman/digital` - Untuk peminjaman digital

## File yang Dimodifikasi
1. `resources/views/peminjaman/index.blade.php` - Dropdown pilihan dan modal
2. `resources/views/peminjaman/create.blade.php` - Indikator jenis peminjaman
3. `app/Http/Controllers/PeminjamanArsipController.php` - Method storeDigital()
4. `app/Models/PeminjamanArsip.php` - Tambah fillable jenis_peminjaman
5. `routes/web.php` - Route peminjaman digital
6. `database/migrations/xxx_add_jenis_peminjaman_to_peminjaman_arsips_table.php` - Migration

## Akses Role
- **Admin**: Dapat menggunakan semua fitur peminjaman
- **Peminjam**: Dapat menggunakan kedua jenis peminjaman, namun peminjaman fisik memerlukan persetujuan admin
- **Operator**: Sesuai dengan permission yang diberikan

## Pengembalian Arsip

### Peminjaman Fisik
- **Hanya admin** yang dapat melakukan pengembalian arsip fisik
- Peminjam tidak bisa mengembalikan arsip fisik sendiri
- **Tombol "Kembalikan" ditampilkan untuk admin** di halaman index dan detail untuk peminjaman fisik yang sudah disetujui
- Admin dapat mengembalikan arsip fisik langsung dari halaman index atau melalui halaman detail

### Peminjaman Digital
- **Peminjam dapat mengembalikan sendiri** arsip yang mereka pinjam secara digital
- Tombol "Kembalikan" ditampilkan di halaman index dan detail untuk peminjaman digital
- Proses pengembalian menggunakan form yang sama
- Status otomatis berubah menjadi "dikembalikan"

### Tampilan Tombol Kembalikan
- **Halaman Index**: 
  - Peminjam: Hanya peminjaman digital yang menampilkan tombol kembalikan
  - Admin: Hanya peminjaman fisik yang sudah disetujui yang menampilkan tombol kembalikan
- **Halaman Detail Fisik**: Hanya admin yang melihat tombol kembalikan
- **Halaman Detail Digital**: Peminjam yang meminjam bisa melihat tombol kembalikan

### Validasi Pengembalian
- Controller memeriksa jenis peminjaman (fisik/digital)
- View menampilkan tombol pengembalian sesuai dengan role dan jenis peminjaman
- Form pengembalian memberikan informasi yang berbeda untuk masing-masing jenis peminjaman
