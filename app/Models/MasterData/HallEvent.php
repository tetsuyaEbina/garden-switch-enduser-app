<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

class HallEvent extends Model
{
    protected $connection = 'mysql2';
    protected $table      = 'hall_events';

    // 読み取り専用
    protected $fillable = [];

    // リレーション:1つのイベントに複数のホールイベント(hall_event_days)
    public function hallEventDays()
    {
        return $this->hasMany(HallEventDay::class, 'event_id', 'event_id');
    }
}
