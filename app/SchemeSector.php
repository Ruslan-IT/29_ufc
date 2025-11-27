<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeSector extends Model
{
	use SoftDeletes;

    protected $table = 'scheme_sector';
}
