<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is petugas
     */
    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    /**
     * Check if user is peminjam
     */
    public function isPeminjam(): bool
    {
        return $this->role === 'peminjam';
    }

    /**
     * Get department name
     */
    public function getDepartmentName(): string
    {
        return $this->department ?? 'Tidak ada departemen';
    }

    /**
     * Get all available departments
     */
    public static function getAvailableDepartments(): array
    {
        return [
            'Seksi Kesejahteraan Sosial',
            'Seksi Pemberdayaan Masyarakat',
            'Seksi Pemerintahan',
            'Seksi Ekonomi Pembangunan dan Lingkungan Hidup',
            'Seksi Ketentraman/Ketertiban',
            'Sekretariat Kepegawaian dan Umum',
            'Sekretariat Program Keuangan'
        ];
    }

    /**
     * Get peminjaman relationships
     */
    public function peminjaman()
    {
        return $this->hasMany(PeminjamanArsip::class, 'peminjam_user_id');
    }
}