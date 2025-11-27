<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scheme extends Model
{
	use SoftDeletes;

    protected $table = 'scheme';

	public function getLocationName() {
		$location = Location::find($this->location_id);
		return $location ? $location->name : '';
	}
}
