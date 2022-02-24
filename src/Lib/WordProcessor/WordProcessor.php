<?php

namespace Up\Lib\WordProcessor;

class WordProcessor
{
	public static function formatWord(int $amount, string $word): string
	{
		if (in_array($amount % 10, [0, 5, 6, 7, 8, 9]) || in_array($amount % 100, [11, 12, 13, 14]))
		{
			return $word . 'ов';
		}

		if ($amount % 10 === 1)
		{
			return $word;
		}

		return $word . 'a';
	}
}