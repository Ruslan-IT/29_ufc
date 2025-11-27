<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
	use SoftDeletes;

    protected $table = 'order';

	public function tickets()
	{
		return $this->hasMany('App\Ticket', 'order_id', 'id');
	}
}
