<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class License extends Model
{

	use SoftDeletes;

	public function maker()
	{
		return $this->belongsTo(Maker::class);
	}

	/**
	 * ラインセンスの内容を保存するメソッド
	 * 
	 * @param Request $request
	 */
	public function saveAll(Request $request)
	{
		$this->name = $request->name;
		$this->maker_id = $request->maker_id;
		$this->product_key = ($request->product_key !== '') ? $request->product_key : null;
		$this->number = $request->number;
		$this->expired_on = ($request->expired_on !== '') ? User::getStdDate($request->expired_on) : null;

		$this->save();
	}

	/**
	 * バリデーションルールを配列で返すメソッド
	 * 
	 * @return array
	 */
	public static function getValidationRule()
	{
		return [
			'name' => 'required|max:200',
			'maker_id' => 'required',
			'product_key' => 'max:100',
			'number' => 'required|max:50|integer',
			'expired_on' => 'date_format:Y年m月d日',
		];
	}

}
