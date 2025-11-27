<?php

namespace App\InterventionImages;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class TemplateMini implements FilterInterface
{
	public function applyFilter(Image $image)
	{
		return $image->fit(170);
	}
}