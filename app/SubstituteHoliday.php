<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubstituteHoliday extends Model
{

    const PAGE_NUM = 10;

    protected $table = 'substitute_holidays';
    protected $fillable = ['workday', 'holiday', 'memo', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
