<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventPrice extends Model
{
	use SoftDeletes;

    protected $table = 'event_price';

	public function place()
	{
		return $this->hasOne('App\SchemePlace', 'id', 'place_id');
	}
}
