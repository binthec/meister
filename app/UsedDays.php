<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsedDays extends Model
{

	protected $table = 'used_days';

	public function user()
	{
		return $this->belongsTo('App¥User');
	}

	const PAGE_NUM = 10;

}
