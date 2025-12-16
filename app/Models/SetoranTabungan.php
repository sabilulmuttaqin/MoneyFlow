<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetoranTabungan extends Model
{
    protected $table = 'setoran_tabungan';

    protected $fillable = [
        'tabungan_id',
        'jumlah'
    ];

    public function tabungan()
    {
        return $this->belongsTo(Tabungan::class);
    }
}
