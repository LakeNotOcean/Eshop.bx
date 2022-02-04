<?php

namespace Up\Service;

use Up\Core\DAO\ItemDAO;
use Up\Core\DAO\ItemDAOmysql;
use Up\Core\DataBase\BaseDatabase;
use Up\Core\DataBase\DefaultDatabase;
use Up\Core\Entity\ItemDetail;

class CatalogServiceImpl implements CatalogService
{
	protected $itemDAO;

	public function __construct(ItemDAO $itemDAO)
	{
		$this->itemDAO = $itemDAO;
	}

	public function getItems(): array
	{
		$items = $this->itemDAO->getItems(0);
		return $items;
	}

	public function getItemById(int $id): ItemDetail
	{
		$item = $this->itemDAO->getItemDetailById($id);
		return $item;
	}

	public function getResultCount(): int
	{
		return 2;
	}

}
