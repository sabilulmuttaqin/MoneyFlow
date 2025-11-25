<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tabungan extends Model
{
    protected $table = 'tabungan';

    protected $fillable = [
        'user_id',
        'nama',
        'target',
        'setoran_awal',
        'total_setoran',
        'tenggat'
    ];

    public function setoran()
    {
        return $this->hasMany(SetoranTabungan::class, 'tabungan_id');
    }

    public function getTotalProgressAttribute()
    {
        $total = $this->total_setoran + ($this->setoran_awal ?? 0);
        return $total;
    }
}
