<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
	use SoftDeletes;

    protected $table = 'ticket';

	public function event()
	{
		return $this->hasOne('App\Event', 'id', 'event_id');
	}
}
