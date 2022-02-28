<?php

namespace Up\Lib\FormatHelper;

class WordFormatter
{
	/**
	 * @param int $count
	 * @param array<string> $forms
	 *
	 * @return string
	 *@example getPlural(42, ['товар', 'товара', 'товаров'])
	 *
	 */
	public static function getPlural(int $count, array $forms): string
	{
		$forms = array_values($forms);
		$cases = array(2, 0, 1, 1, 1, 2);
		return $forms[($count % 100 > 4 && $count % 100 < 20) ? 2 : $cases[min($count % 10, 5)]];
	}
}
