<?php

namespace Up\DAO\ItemDAO;

use Up\Entity\ItemDetail;


interface ItemDAOInterface
{
	public function getItems(int $offset, int $amountItems): array;

	public function getItemDetailById(int $id): ItemDetail;

	public function save(ItemDetail $item): ItemDetail;
}
