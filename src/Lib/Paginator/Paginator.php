<?php

namespace Up\Lib\Paginator;

class Paginator
{
	public static function getLimitOffset(int $page, int $objectsInPageCount)
	{
		return ['offset' => ($page - 1) * $objectsInPageCount, 'amountItems' => $objectsInPageCount];
	}
}