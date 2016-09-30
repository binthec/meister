<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\User;

class PaidVacation extends Model
{

	protected $table = 'paid_vacations';

	public function user()
	{
		return $this->belongsTo('App¥User');
	}

}
