<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchiveDestruction extends Model
{
    protected $fillable = [
        'arsip_id',
        'jre_id',
        'user_id',
        'destruction_notes',
        'destruction_method',
        'destruction_location',
        'destruction_witnesses',
        'destroyed_at'
    ];

    protected $casts = [
        'destroyed_at' => 'datetime',
    ];

    public function arsip()
    {
        return $this->belongsTo(Arsip::class);
    }

    public function jre()
    {
        return $this->belongsTo(Jre::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function destroyer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDestructionMethodTextAttribute()
    {
        $methods = [
            'shredding' => 'Penghancuran Fisik (Shredding)',
            'burning' => 'Pembakaran',
            'digital_deletion' => 'Penghapusan Digital',
            'chemical_treatment' => 'Perlakuan Kimia',
            'other' => 'Lainnya'
        ];

        return $methods[$this->destruction_method] ?? $this->destruction_method;
    }
}
