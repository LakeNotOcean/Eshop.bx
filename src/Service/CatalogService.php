<?php

namespace Up\Service;

use Up\Core\DAO\ItemDAOmysql;
use Up\Core\DataBase\BaseDatabase;
use Up\Core\DataBase\DefaultDatabase;
use Up\Model\Item;

class CatalogService
{
	public static function getItems(): array
	{
		$DAO = new ItemDAOmysql(DefaultDatabase::getInstance());
		$items = $DAO->getItems(0);
		return $items;
	}

	public static function getResultCount(): int
	{
		return 2;
	}

}
