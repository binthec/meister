<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    const STATUS_STRAT_WORKING = 10;
    const STATUS_END_WORKING = 90;
    const STATUS_UNKNOWN = 99;

    protected $statusLabels = [
        self::STATUS_STRAT_WORKING => '出勤',
        self::STATUS_END_WORKING => '退勤',
        self::STATUS_UNKNOWN => '不明'
    ];

    public function getStatusLabel()
    {
        return $this->statusLabels[$this->status];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
