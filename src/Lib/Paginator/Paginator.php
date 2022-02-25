<?php

namespace Up\Lib\Paginator;


class Paginator
{
	public static function getLimitOffset(int $currentPage, int $objectsInPageCount): array
	{
		return ['offset' => ($currentPage - 1) * $objectsInPageCount, 'amountItems' => $objectsInPageCount];
	}

	public static function getPageCount(int $itemCount, int $itemInPageCount): int
	{
		$lastPageOffset = $itemCount % $itemInPageCount === 0 ? 0 : 1;

		return intdiv($itemCount, $itemInPageCount) + $lastPageOffset;
	}
}
