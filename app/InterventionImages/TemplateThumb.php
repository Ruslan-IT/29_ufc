<?php

namespace App\InterventionImages;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class TemplateThumb implements FilterInterface
{
	public function applyFilter(Image $image)
	{
		return $image->fit(365, 365);
	}
}