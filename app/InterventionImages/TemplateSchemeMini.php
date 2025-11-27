<?php

namespace App\InterventionImages;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class TemplateSchemeMini implements FilterInterface
{
	public function applyFilter(Image $image)
	{
		return $image->widen(500);
	}
}