<?php

namespace Up\DAO;

use Up\Entity\ItemDetail;

Interface ItemDAO
{
	public function getItems(int $page): array;
	public function getItemDetailById(int $id): ItemDetail;
}