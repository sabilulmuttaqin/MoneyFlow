<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tabungan extends Model
{
    use HasFactory;

    protected $table = 'tabungan';

    protected $fillable = [
        'user_id',
        'nama',
        'target',
        'setoran_awal',
        'tanggal_target',
    ];

    /* =====================
     * RELATIONSHIP
     * ===================== */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setoran()
    {
        return $this->hasMany(SetoranTabungan::class);
    }

        public function getTotalTerkumpulAttribute()
    {
        $totalSetoran = $this->setoran()
            ->where('tipe', 'setor')
            ->sum('jumlah');

        $totalTarik = $this->setoran()
            ->where('tipe', 'tarik')
            ->sum('jumlah');

        return ($this->setoran_awal ?? 0) + $totalSetoran - $totalTarik;
    }

        public function getProgressAttribute()
    {
        if ($this->target <= 0) {
            return 0;
        }

        return min(
            ($this->total_terkumpul / $this->target) * 100,
            100
        );
    }
}

