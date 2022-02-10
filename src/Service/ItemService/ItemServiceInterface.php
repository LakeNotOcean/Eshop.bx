<?php

namespace Up\Service\ItemService;

use Up\Entity\ItemDetail;

interface ItemServiceInterface
{
	public function getItems(): array;

	public function getItemById(int $id): ItemDetail;
}