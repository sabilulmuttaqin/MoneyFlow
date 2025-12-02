<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'user_id',  // TAMBAHKAN INI
        'name',
        'icon',
        'budget',
        'used',
        'type',    // pengeluaran / pemasukan
        'status',  // kebutuhan pokok / keinginan
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Hitung persentase progress
    public function getProgressAttribute()
    {
        if ($this->budget == 0) {
            return 0;
        }

        return round(($this->used / $this->budget) * 100);
    }

    // Scope untuk filter kategori milik user yang login
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}