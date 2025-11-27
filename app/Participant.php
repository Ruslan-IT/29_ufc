<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Participant extends Model
{
	use SoftDeletes;

    protected $table = 'participant';

	protected static $countries = [
		1 => [
			'name' => 'Великобритания',
			'code' => 'gbr'
		],
		2 => [
			'name' => 'Международные коллективы',
			'code' => 'int'
		],
		3 => [
			'name' => 'Мексика',
			'code' => 'mex'
		],
		4 => [
			'name' => 'Монако',
			'code' => 'mon'
		],
		5 => [
			'name' => 'Нидерланды',
			'code' => 'ned'
		],
		6 => [
			'name' => 'Оман',
			'code' => 'oma'
		],
		7 => [
			'name' => 'Швейцария',
			'code' => 'sui'
		],
		8 => [
			'name' => 'Шри-Ланка',
			'code' => 'sri'
		],
		9 => [
			'name' => 'Россия',
			'code' => 'rus'
		]
	];

	public static function getCountries()
	{
		$result = [];
		foreach (self::$countries as $key => $country) {
			$result[$key] = $country['name'];
		}
		return $result;
	}

	public static function getCountryById($contry_id = 0)
	{
		if (isset(self::$countries[$contry_id])) {
			return self::$countries[$contry_id];
		}

		return [];
	}
}
