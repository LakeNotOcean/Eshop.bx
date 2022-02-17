<?php

namespace Up\DAO\ItemDAO;

use Up\Entity\Item;
use Up\Entity\ItemDetail;


interface ItemDAOInterface
{
	public function getItems(int $offset, int $amountItems): array;

	public function getItemsByOrderId(int $orderId): array;

	public function getItemDetailById(int $id): ItemDetail;

	public function save(ItemDetail $item): ItemDetail;

	public function deactivateItem(int $id): void;

	public function updateCommonInfo(Item $item): Item;
}
