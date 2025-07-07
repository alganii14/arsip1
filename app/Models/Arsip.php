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
        'retention_years',
        'is_archived_to_jre',
        'archived_to_jre_at',
        'has_retention_notification'
    ];

    protected $casts = [
        'tanggal_arsip' => 'date',
        'retention_date' => 'date',
        'archived_to_jre_at' => 'datetime',
    ];

    // Scopes for filtering based on JRE status
    public function scopeActive($query)
    {
        return $query->where('is_archived_to_jre', false);
    }

    public function scopeArchivedToJre($query)
    {
        return $query->where('is_archived_to_jre', true);
    }

    public function scopeAvailableForBorrowing($query)
    {
        return $query->where('is_archived_to_jre', false);
    }

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

    public function calculateRetentionDate($manualYears = null)
    {
        // Only calculate if we have tanggal_arsip
        if (!$this->tanggal_arsip) {
            return;
        }

        // Determine years to add
        $years = 5; // Default 5 years
        if ($manualYears !== null) {
            $years = intval($manualYears);
        } elseif ($this->retention_years) {
            $years = intval($this->retention_years);
        }

        try {
            // Get tanggal_arsip as string
            $tanggalArsipStr = $this->tanggal_arsip;
            if ($this->tanggal_arsip instanceof Carbon) {
                $tanggalArsipStr = $this->tanggal_arsip->format('Y-m-d');
            }

            // Parse tanggal_arsip and add years
            $tanggalArsip = Carbon::createFromFormat('Y-m-d', $tanggalArsipStr);
            $retentionDate = $tanggalArsip->addYears($years);

            // Update the retention_date and retention_years
            $this->update([
                'retention_date' => $retentionDate->format('Y-m-d'),
                'retention_years' => $years
            ]);

        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error("Error calculating retention date for arsip {$this->id}: " . $e->getMessage());
            throw new \Exception("Failed to calculate retention date: " . $e->getMessage());
        }
    }

    public function shouldMoveToJre()
    {
        if (!$this->is_archived_to_jre && $this->retention_date) {
            $today = Carbon::today()->format('Y-m-d');
            // Get retention date from attributes array as string
            $retentionDateStr = $this->attributes['retention_date'];
            return $today >= $retentionDateStr;
        }
        return false;
    }

    public function shouldShowRetentionNotification()
    {
        // Since we auto-move to JRE, this is only for display purposes
        if (!$this->is_archived_to_jre && $this->retention_date) {
            $today = Carbon::today()->format('Y-m-d');
            $retentionDateStr = $this->attributes['retention_date'];
            return $today >= $retentionDateStr;
        }
        return false;
    }

    public function autoMoveToJreIfExpired()
    {
        if ($this->shouldMoveToJre()) {
            $this->has_retention_notification = true;
            $this->save();
            return $this->moveToJre();
        }
        return null;
    }

    public function moveToJre($notes = null)
    {
        // Store arsip data for JRE
        $arsipData = $this->toArray();

        // Create JRE record with full arsip data
        $jre = Jre::create([
            'arsip_id' => $this->id,
            'status' => 'inactive',
            'notes' => $notes,
            'processed_at' => Carbon::now(),
            'arsip_data' => json_encode($arsipData) // Store complete arsip data
        ]);

        // Mark as archived to JRE
        $this->is_archived_to_jre = true;
        $this->archived_to_jre_at = Carbon::now();
        $this->save();

        return $jre;
    }
}
