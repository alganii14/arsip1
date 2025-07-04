<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Arsip extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama_dokumen',
        'kategori',
        'tanggal_arsip',
        'keterangan',
        'rak',
        'file_path',
        'file_type',
        'retention_date',
        'is_archived_to_jre',
        'archived_to_jre_at',
        'has_retention_notification'
    ];

    protected $casts = [
        'tanggal_arsip' => 'date',
        'retention_date' => 'date',
        'archived_to_jre_at' => 'datetime',
    ];

    public function jre()
    {
        return $this->hasOne(Jre::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(PeminjamanArsip::class);
    }

    public function isCurrentlyBorrowed()
    {
        return $this->peminjaman()
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->where('confirmation_status', 'approved')
            ->exists();
    }

    public function getCurrentBorrower()
    {
        return $this->peminjaman()
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->latest()
            ->first();
    }

    public function calculateRetentionDate()
    {
        // Set retention date to 5 years after tanggal_arsip
        if ($this->tanggal_arsip) {
            $date = Carbon::parse($this->attributes['tanggal_arsip'])->addYears(5);
            $this->attributes['retention_date'] = $date->format('Y-m-d');
            $this->save();
        }
    }

    public function shouldMoveToJre()
    {
        if (!$this->is_archived_to_jre && $this->retention_date) {
            $today = Carbon::today();
            $retentionDate = Carbon::parse($this->attributes['retention_date']);
            return $today->gte($retentionDate);
        }
        return false;
    }

    public function shouldShowRetentionNotification()
    {
        // Since we auto-move to JRE, this is only for display purposes
        if (!$this->is_archived_to_jre && $this->retention_date) {
            $today = Carbon::today();
            $retentionDate = Carbon::parse($this->attributes['retention_date']);
            return $today->gte($retentionDate);
        }
        return false;
    }

    public function autoMoveToJreIfExpired()
    {
        if ($this->shouldMoveToJre()) {
            $this->has_retention_notification = true;
            $this->save();
            return $this->moveToJre('Automatically moved to JRE when retention date reached');
        }
        return null;
    }

    public function moveToJre($notes = null)
    {
        $this->is_archived_to_jre = true;
        $this->archived_to_jre_at = Carbon::now();
        $this->save();

        // Create JRE record with inactive status
        return Jre::create([
            'arsip_id' => $this->id,
            'status' => 'inactive', // Changed from 'active' to 'inactive' as requested
            'notes' => $notes,
            'processed_at' => Carbon::now()
        ]);
    }
}
