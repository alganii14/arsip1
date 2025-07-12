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
        'file_path',
        'file_type',
        'retention_date',
        'retention_years',
        'is_archived_to_jre',
        'archived_to_jre_at',
        'has_retention_notification',
        'created_by'
    ];

    protected $casts = [
        'tanggal_arsip' => 'datetime',
        'retention_date' => 'datetime',
        'archived_to_jre_at' => 'datetime',
    ];

    // Scopes for filtering based on JRE status
    public function scopeActive($query)
    {
        return $query->where('is_archived_to_jre', false)
                    ->whereDoesntHave('pemindahan', function($q) {
                        $q->whereIn('status', ['approved', 'completed']);
                    });
    }

    public function scopeArchivedToJre($query)
    {
        return $query->where('is_archived_to_jre', true);
    }

    public function scopeAvailableForBorrowing($query)
    {
        return $query->where('is_archived_to_jre', false);
    }

    // Scope untuk filter arsip milik user tertentu
    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    // Scope untuk filter arsip yang bisa diakses user (milik sendiri atau yang dipinjam)
    public function scopeAccessibleBy($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('created_by', $userId) // Arsip milik sendiri
              ->orWhereHas('peminjaman', function($peminjamanQuery) use ($userId) {
                  $peminjamanQuery->where('peminjam_user_id', $userId)
                                 ->where('confirmation_status', 'approved')
                                 ->whereIn('status', ['dipinjam', 'terlambat']);
              });
        });
    }

    public function jre()
    {
        return $this->hasOne(Jre::class, 'arsip_id');
    }

    public function peminjaman()
    {
        return $this->hasMany(PeminjamanArsip::class);
    }

    public function pemindahan()
    {
        return $this->hasMany(Pemindahan::class);
    }

    public function isAlreadyMoved()
    {
        return $this->pemindahan()
            ->whereIn('status', ['approved', 'completed'])
            ->exists();
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
        // Don't move if already archived to JRE
        if ($this->is_archived_to_jre) {
            return false;
        }

        // Check if JRE already exists for this arsip (safe check)
        $jreExists = Jre::where('arsip_id', $this->id)->exists();
        if ($jreExists) {
            return false;
        }

        if ($this->retention_date) {
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
        // Check if JRE already exists for this arsip (safe check without loading relation)
        $existingJre = Jre::where('arsip_id', $this->id)->first();
        if ($existingJre) {
            // If JRE already exists, just return it instead of creating a new one
            // Update arsip status if needed
            if (!$this->is_archived_to_jre) {
                $this->is_archived_to_jre = true;
                $this->archived_to_jre_at = Carbon::now();
                $this->save();
            }
            return $existingJre;
        }

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

    // Relasi
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
