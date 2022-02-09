<?php

namespace Up\DAO\ItemDAO;

use Up\Entity\ItemDetail;

interface ItemDAO
{
	public function getItems(int $page): array;

	public function getItemDetailById(int $id): ItemDetail;
}