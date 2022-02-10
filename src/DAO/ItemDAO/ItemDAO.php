<?php

namespace Up\DAO\ItemDAO;

use Up\Entity\ItemDetail;

interface ItemDAO
{
	public function getItems(int $offset, int $amountItems): array;

	public function getItemDetailById(int $id): ItemDetail;
}