<?php

namespace App\InterventionImages;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class TemplateFrontSlider implements FilterInterface
{
	public function applyFilter(Image $image)
	{
		return $image->fit(380, 220);
	}
}