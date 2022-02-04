<?php

namespace Up\Core\DAO;

use Up\Core\Entity\ItemDetail;

Interface ItemDAO
{
	public function getItems(int $page): array;
	public function getItemDetailById(int $id): ItemDetail;
}