<?php

namespace Up\Lib\FormatHelper;

class WordEndingResolver
{
	/**
	 * @param int $number
	 * @param array<string> $titles
	 *
	 * @return string
	 */
	public static function resolve(int $number, array $titles): string
	{
		$titles = array_values($titles);
		$cases = array(2, 0, 1, 1, 1, 2);
		return $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
	}
}