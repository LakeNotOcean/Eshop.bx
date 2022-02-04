<?php

namespace Up\Service;

use Up\Core\DataBase\BaseDatabase;
use Up\Model\Item;

class CatalogService
{
	public static function getItems(BaseDatabase $bd): array
	{
		$query = '
		Select *
		FROM up_item
		';
		$result = mysqli_query($bd,$query);
		$result = mysqli_fetch_assoc($result);
		$items = [];
		foreach ($result as $item)
		{
			 $items = [new Item([
				'ID' => $result[ID],
				'TITLE' => $result[TITLE],
				'PRICE' => $result[PRICE],
				'SHORT_DESC' => $result[SHORT_DESC],
				'FULL_DESC' => $result[FULL_DESC],
				'SPECS' => []])];
		}
		return $items;
	}

	public static function getResultCount(): int
	{
		return 2;
	}

}
