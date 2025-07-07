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
        'arsip_data'
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    // Scope untuk filter arsip yang belum dimusnahkan
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'destroyed');
    }

    public function scopeDestroyed($query)
    {
        return $query->where('status', 'destroyed');
    }

    // Methods for handling recovery
    public function recoverToArsip($recoveryYears = null)
    {
        $arsip = $this->arsip;

        // Use recovery_years from JRE record, or default to original retention years
        $years = $recoveryYears ?? $this->recovery_years ?? $arsip->retention_years ?? 5;

        // Update arsip to make it active again
        $arsip->is_archived_to_jre = false;
        $arsip->archived_to_jre_at = null;
        $arsip->has_retention_notification = false;

        // Extend retention period from current date
        $arsip->retention_date = \Carbon\Carbon::now()->addYears($years);
        $arsip->retention_years = $years;
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
}
