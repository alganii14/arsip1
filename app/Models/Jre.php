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
        'processed_at'
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    public function arsip()
    {
        return $this->belongsTo(Arsip::class);
    }
}