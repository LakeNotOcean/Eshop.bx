<?php

namespace Up\Core\DAO;

use Up\Core\Entity\Item;

class ItemDAOmysql
{
	private const PAGE_SIZE = 10;
	private $DBConnection;

	public function __construct(\Up\Core\DataBase\DefaultDatabase $DBConnection)
	{
		$this->DBConnection = $DBConnection;
	}

	public function getItems(int $page): array
	{
		$from = $page * 10;
		$to = ($page + 1) * 10;
		$sql = "SELECT ID, TITLE, PRICE, SORT_ORDER, SHORT_DESC, ACTIVE FROM up_item ui
				ORDER BY ui.SORT_ORDER
				LIMIT {$from}, {$to};";
		$result = $this->DBConnection->query($sql);
		$items = [];
		while ($row = mysqli_fetch_assoc($result))
		{
			$item = new Item();
			$this->mapItem($item, $row);
			$items[] = $item;
		}
		return $items;
	}

	private function mapItem(Item $item, array $row)
	{
		$item->setId($row['ID']);
		$item->setTitle($row['TITLE']);
		$item->setPrice($row['PRICE']);
		$item->setShortDescription($row['SHORT_DESC']);
		$item->setSortOrder($row['SORT_ORDER']);
		$item->setIsActive($row['ACTIVE']);
	}
}