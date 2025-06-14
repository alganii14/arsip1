<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PeminjamanArsip extends Model
{
    use HasFactory;

    protected $fillable = [
        'arsip_id',
        'peminjam_user_id',
        'peminjam',
        'jabatan',
        'departemen',
        'kontak',
        'tanggal_pinjam',
        'tanggal_kembali',
        'batas_waktu',
        'status',
        'tujuan_peminjaman',
        'catatan',
        'petugas_peminjaman',
        'petugas_pengembalian',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'batas_waktu' => 'date',
    ];

    public function arsip()
    {
        return $this->belongsTo(Arsip::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'peminjam_user_id');
    }

    public function isOverdue()
    {
        if ($this->status === 'dikembalikan') {
            return false;
        }
        
        return Carbon::now()->gt($this->batas_waktu);
    }

    public function updateStatus()
    {
        if ($this->status === 'dikembalikan') {
            return;
        }

        if ($this->isOverdue()) {
            $this->status = 'terlambat';
            $this->save();
        }
    }

    public function getDurasiPinjam()
    {
        if ($this->tanggal_kembali) {
            return $this->tanggal_pinjam->diffInDays($this->tanggal_kembali) + 1;
        }
        
        if ($this->status === 'dipinjam') {
            return $this->tanggal_pinjam->diffInDays(Carbon::now()) + 1;
        }
        
        return null;
    }
}