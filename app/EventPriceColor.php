<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class EventPriceColor extends Model
{
	use SoftDeletes;

    protected $table = 'event_price_color';
}
