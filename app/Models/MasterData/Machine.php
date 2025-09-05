<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $connection = 'mysql2';
    protected $table      = 'machines';

    protected $fillable = [];
}