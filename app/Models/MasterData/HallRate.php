<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

class HallRate extends Model
{
    protected $connection = 'mysql2';
    protected $table      = 'hall_rates';

    // 読み取り専用
    protected $fillable = [];


    // リレーション:1つのホールに複数の貸玉(rate)
    public function hall()
    {
        return $this->belongsTo(Hall::class, 'hall_id', 'hall_id');
    }
}
