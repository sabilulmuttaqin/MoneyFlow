<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'user_id',
        'anggaran_id', // otomatis ambil dari tabel anggaran terbaru
        'name',
        'icon',
        'budget',
        'used',
        'type',
        'status',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Anggaran
    public function anggaran()
    {
        return $this->belongsTo(Anggaran::class);
    }

    // Hitung persentase progress
    public function getProgressAttribute()
    {
        if ($this->budget == 0) {
            return 0;
        }
        return round(($this->used / $this->budget) * 100);
    }

    // Scope untuk filter kategori milik user
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Method static untuk otomatis ambil anggaran_id terbaru
    public static function getLatestAnggaranId()
    {
        $latest = \App\Models\Anggaran::latest('id')->first();
        return $latest ? $latest->id : null;
    }

    // Boot method untuk otomatis set anggaran_id saat create
    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->anggaran_id)) {
                $category->anggaran_id = self::getLatestAnggaranId();
            }
        });
    }
}
