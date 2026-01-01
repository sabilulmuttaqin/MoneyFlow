<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetoranTabungan extends Model
{
    use HasFactory;

    protected $table = 'setoran_tabungan';

    protected $fillable = [
        'tabungan_id',
        'user_id',
        'jumlah',
        'tipe',
        'keterangan',
        'tanggal',
    ];

    /* =====================
     * RELATIONSHIP
     * ===================== */

    public function tabungan()
    {
        return $this->belongsTo(Tabungan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
