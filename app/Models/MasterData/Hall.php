<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    // 接続DBを明示
    protected $connection = 'mysql2';
    protected $table      = 'halls';

    // 読み取り専用
    protected $fillable = [];

    // リレーション:1つのホールに複数の貸玉(rate)
    public function hallRates()
    {
        return $this->hasMany(HallRate::class, 'hall_id', 'hall_id');
    }

    // リレーション:1つのホールに複数のイベント(hall_event_days)
    public function hallEventDays()
    {
        return $this->hasMany(HallEventDay::class, 'hall_id', 'hall_id');
    }
}
