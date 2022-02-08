<?php

namespace Up\DAO;

use Up\Entity\Item;
use Up\Entity\ItemDetail;

interface SpecificationDAO
{
	public function getCategoriesByItemTypeId(int $itemTypeId): array;

	public function getItemCategoriesByItem(ItemDetail $item): array;

}