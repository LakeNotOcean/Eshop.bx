<?php

namespace Up\Lib\Paginator;

class Paginator
{
	public static function getLimitOffset(int $page, int $objectsInPageCount)
	{
		return ['limit' => $objectsInPageCount, 'offset' => ($page - 1) * $objectsInPageCount];
	}
}