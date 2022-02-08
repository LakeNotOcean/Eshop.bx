<?php

namespace Up\DAO;

use Up\Core\Database\DefaultDatabase;
use Up\Entity\Item;
use Up\Entity\ItemDetail;
use Up\Entity\Specification;
use Up\Entity\SpecificationCategory;

class SpecificationDAOmysql implements SpecificationDAO
{
	private $DBConnection;

	public function __construct(DefaultDatabase $DBConnection)
	{
		$this->DBConnection = $DBConnection;
	}

	public function getCategoriesByItemTypeId(int $itemTypeId): array
	{
		$result = $this->DBConnection->query($this->getCategoriesByItemTypeIdQuery($itemTypeId));
		$resultArray = [];
		while ($row = $result->fetch())
		{
			$categoryId = $row['CAT_ID'];
			if (!array_key_exists($categoryId, $resultArray))
			{
				$resultArray[$categoryId] = new SpecificationCategory(
					$row['CAT_ID'], $row['CAT_NAME'], $row['CAT_ORDER']
				);
			}
			$specId = $row['SPEC_ID'];
			$specification = new Specification(
				$specId, $row['SPEC_NAME'], $row['SPEC_TYPE'], $row['SPEC_ORDER']
			);
			$resultArray[$categoryId]->addToSpecificationList($specification);
		}

		return $resultArray;
	}

	public function getItemCategoriesByItem(ItemDetail $item): array
	{
		$CategoriesList = $this->getCategoriesByItemTypeId(($item->getItemType()->getId()));
		$queryResult = $this->DBConnection->query($this->getCategoriesByItemIdQuery($item->getId()));
		while ($row = $queryResult->fetch())
		{
			$categoryId = $row['CAT_ID'];
			if (!array_key_exists($categoryId, $CategoriesList))
			{
				continue;
			}
			$specificationId = $row['SPEC_ID'];
			if (!$CategoriesList[$categoryId]->isSpecificationExist($specificationId))
			{
				continue;
			}
			$CategoriesList[$categoryId]->setSpecificationValueById($specificationId,$row['SPEC_VALUE']);
		}

		return $CategoriesList;

	}

	private function getCategoriesByItemTypeIdQuery(int $itemTypeId): string
	{
		return "SELECT 
			usc.ID as CAT_ID,
            usc.NAME as CAT_NAME,
            usc.DISPLAY_ORDER as CAT_ORDER,
            u.ID as SPEC_ID,
            u.NAME as SPEC_NAME,
            u.DISPLAY_ORDER as SPEC_ORDER,
            u.TYPE as SPEC_TYPE
		FROM up_spec_template ust 
		INNER JOIN up_spec_type u on ust.SPEC_TYPE_ID = u.ID
		INNER JOIN up_spec_category usc on u.SPEC_CATEGORY_ID = usc.ID
		WHERE ITEM_TYPE_ID={$itemTypeId}
		";
	}

	private function getCategoriesByItemIdQuery(int $itemId): string
	{
		return "SELECT
			usc.ID as CAT_ID,
            uis.SPEC_TYPE_ID as SPEC_ID,
			uis.VALUE as SPEC_VALUE
		FROM up_item_spec uis
		INNER JOIN up_spec_type ust on uis.SPEC_TYPE_ID = ust.ID
		INNER JOIN up_spec_category usc on ust.SPEC_CATEGORY_ID = usc.ID
		WHERE uis.ITEM_ID={$itemId}";
	}
}