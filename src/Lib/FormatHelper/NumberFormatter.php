<?php

namespace Up\Lib\FormatHelper;

class NumberFormatter
{
	public static function ratingFormat(float $rating): string
	{
		return number_format($rating, 1, '.', '');
	}
}