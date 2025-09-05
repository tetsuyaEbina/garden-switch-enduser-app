<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

class HallEventDay extends Model
{

    // 接続DBを明示
    protected $connection = 'mysql2';
    protected $table      = 'hall_event_days';

    // 読み取り専用
    protected $fillable = [];

    /**
     * ホールとのリレーション
     */
    public function hall()
    {
        return $this->belongsTo(Hall::class, 'hall_id', 'hall_id');
    }

    /**
     * イベントマスターとのリレーション
     */
    public function event()
    {
        return $this->belongsTo(HallEvent::class, 'event_id', 'event_id');
    }
}
