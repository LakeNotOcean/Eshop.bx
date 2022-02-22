<?php

namespace Up\Service\ItemService;

use Up\Entity\Item;
use Up\Entity\ItemDetail;


interface ItemServiceInterface
{
	public function getItems(array $limitOffset): array;

	public function getItemById(int $id): ItemDetail;

	public function save(ItemDetail $item): ItemDetail;

	public function deactivateItem(int $id): void;

	public function activateItem(int $id): void;

	public function updateCommonInfo(Item $item): Item;
}
