<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemindahan extends Model
{
    use HasFactory;

    protected $table = 'pemindahans';

    protected $fillable = [
        'arsip_id',
        'user_id',
        'tingkat_perkembangan',
        'jumlah_folder',
        'keterangan',
        'status',
        'approved_by',
        'approved_at',
        'catatan_admin',
        'completed_by',
        'completed_at',
        'catatan_penyelesaian',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function arsip()
    {
        return $this->belongsTo(Arsip::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function completer()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $statusMap = [
            'pending' => 'bg-warning',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'completed' => 'bg-info',
        ];

        return $statusMap[$this->status] ?? 'bg-secondary';
    }

    public function getStatusTextAttribute()
    {
        $statusMap = [
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'completed' => 'Selesai',
        ];

        return $statusMap[$this->status] ?? 'Unknown';
    }

    public function getTingkatPerkembanganTextAttribute()
    {
        $tingkatMap = [
            'asli' => 'Asli',
            'copy' => 'Copy',
            'asli_dan_copy' => 'Asli dan Copy',
        ];

        return $tingkatMap[$this->tingkat_perkembangan] ?? $this->tingkat_perkembangan;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
