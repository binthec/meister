<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class License extends Model
{

	use SoftDeletes;

	public function maker()
	{
		return $this->belongsTo(Maker::class);
	}

}
