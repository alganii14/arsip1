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
}
