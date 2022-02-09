<?php

namespace Up\DAO\SpecificationDAO;

use Up\Entity\Item;
use Up\Entity\ItemDetail;

interface SpecificationDAO
{
	public function getCategoriesByItemTypeId(int $itemTypeId): array;

	//public function getItemCategoriesByItem(ItemDetail $item): array;

}