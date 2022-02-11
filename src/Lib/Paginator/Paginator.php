<?php

namespace Up\Lib\Paginator;

class Paginator
{
	public static function getLimitOffset(int $page, int $objectsInPageCount)
	{
		return ['offset' => ($page - 1) * $objectsInPageCount, 'amountItems' => $objectsInPageCount];
	}

	public static function getPageCount(int $itemCount, int $itemInPageCount)
	{
		$lastPageOffset = $itemCount % $itemInPageCount === 0 ? 0 : 1;

		return intdiv($itemCount, $itemInPageCount) + $lastPageOffset;
	}
}