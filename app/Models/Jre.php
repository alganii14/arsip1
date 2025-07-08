<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jre extends Model
{
    use HasFactory;

    protected $fillable = [
        'arsip_id',
        'status',
        'notes',
        'processed_at',
        'recovery_years',
        'arsip_data',
        'transferred_at',
        'transferred_by',
        'transfer_notes',
        'transfer_status'
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'transferred_at' => 'datetime',
    ];

    // Scope untuk filter arsip yang masih aktif di JRE
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['destroyed', 'transferred']);
    }

    public function scopeDestroyed($query)
    {
        return $query->where('status', 'destroyed');
    }

    // Scope untuk arsip yang sudah dipindahkan
    public function scopeTransferred($query)
    {
        return $query->where('status', 'transferred');
    }

    // Methods for handling recovery
    public function recoverToArsip($recoveryYears = null)
    {
        $arsip = $this->arsip;

        // Update arsip to make it active again
        $arsip->is_archived_to_jre = false;
        $arsip->archived_to_jre_at = null;
        $arsip->has_retention_notification = false;

        // Handle permanent retention (when recoveryYears is explicitly null)
        if ($recoveryYears === null) {
            // Permanent retention - set to null
            $arsip->retention_date = null;
            $arsip->retention_years = null;
        } else {
            // Use provided recovery years or default to 5
            $years = $recoveryYears ?: 5;

            // Extend retention period from current date
            $arsip->retention_date = \Carbon\Carbon::now()->addYears($years);
            $arsip->retention_years = $years;
        }

        $arsip->save();

        // Delete JRE record - arsip kembali ke tabel arsip
        $this->delete();

        return $arsip;
    }

    public function recoverWithCustomYears($recoveryYears)
    {
        $arsip = $this->arsip;

        // Handle permanent retention
        if ($recoveryYears === 'permanent') {
            $numericYears = 999;
            $retentionDate = \Carbon\Carbon::now()->addYears(100);
        } else {
            $numericYears = intval($recoveryYears);
            $retentionDate = \Carbon\Carbon::now()->addYears($numericYears);
        }

        // Update arsip to make it active again
        $arsip->is_archived_to_jre = false;
        $arsip->archived_to_jre_at = null;
        $arsip->has_retention_notification = false;
        $arsip->retention_date = $retentionDate;
        $arsip->retention_years = $numericYears;
        $arsip->save();

        // Delete JRE record - arsip kembali ke tabel arsip
        $this->delete();

        return $arsip;
    }

    public function arsip()
    {
        return $this->belongsTo(Arsip::class);
    }

    public function transferrer()
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }
}
