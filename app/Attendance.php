<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    const STATUS_STRAT_WORKING = 10;
    const STATUS_END_WORKING = 90;
    const STATUS_UNKNOWN = 99;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
