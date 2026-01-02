<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Kolom yang dapat diisi mass-assignment
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo',
    ];

    // Kolom yang perlu disembunyikan saat serialisasi (seperti ketika mengonversi ke array atau JSON)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Pengaturan casting untuk kolom tertentu
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the profile photo URL
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo && file_exists(public_path('uploads/profiles/' . $this->profile_photo))) {
            return asset('uploads/profiles/' . $this->profile_photo);
        }
        return asset('images/profile.avif');
    }
}

