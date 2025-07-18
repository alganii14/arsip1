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
        'jenis_peminjaman',
        'peminjam_user_id',
        'peminjam',
        'departemen',
        'kontak',
        'tanggal_pinjam',
        'tanggal_kembali',
        'batas_waktu',
        'status',
        'confirmation_status',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'tujuan_peminjaman',
        'catatan',
        'petugas_peminjaman',
        'petugas_pengembalian',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_kembali' => 'datetime',
        'batas_waktu' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function arsip()
    {
        return $this->belongsTo(Arsip::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'peminjam_user_id');
    }

    /**
     * Relasi dengan admin yang menyetujui
     */
    public function adminApprover()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isOverdue()
    {
        if ($this->status === 'dikembalikan') {
            return false;
        }

        return Carbon::now()->gt(Carbon::parse($this->attributes['batas_waktu']));
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
            $start = Carbon::parse($this->attributes['tanggal_pinjam'])->startOfDay();
            $end = Carbon::parse($this->attributes['tanggal_kembali'])->startOfDay();
            return $start->diffInDays($end) + 1;
        }

        if ($this->status === 'dipinjam') {
            $start = Carbon::parse($this->attributes['tanggal_pinjam'])->startOfDay();
            $now = Carbon::now()->startOfDay();
            return $start->diffInDays($now) + 1;
        }

        return null;
    }

    /**
     * Check if peminjaman is pending for approval
     */
    public function isPendingApproval()
    {
        return $this->confirmation_status === 'pending';
    }

    /**
     * Check if peminjaman is approved
     */
    public function isApproved()
    {
        return $this->confirmation_status === 'approved';
    }

    /**
     * Check if peminjaman is rejected
     */
    public function isRejected()
    {
        return $this->confirmation_status === 'rejected';
    }

    /**
     * Approve peminjaman
     */
    public function approve($adminId)
    {
        $this->update([
            'confirmation_status' => 'approved',
            'approved_by' => $adminId,
            'approved_at' => Carbon::now(),
        ]);
    }

    /**
     * Reject peminjaman
     */
    public function reject($adminId, $reason = null)
    {
        $this->update([
            'confirmation_status' => 'rejected',
            'approved_by' => $adminId,
            'approved_at' => Carbon::now(),
            'rejection_reason' => $reason,
        ]);
    }
}
