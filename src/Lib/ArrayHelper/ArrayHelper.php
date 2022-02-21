<?php

namespace Up\Lib\ArrayHelper;

class ArrayHelper
{
	public static function flatten(array $array, bool $preserveKey)
	{
		$iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array));
		return iterator_to_array($iterator, $preserveKey);
	}
}