<?php

namespace Up\DAO\SpecificationDAO;

use Up\Core\Database\DefaultDatabase;
use Up\Entity\EntityArray;
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

	public function getSpecificationCategoryByName(array $categoryNames, array $typeNames): EntityArray
	{
		$categories = new EntityArray();
		$result = $this->DBConnection->query($this->getSpecificationCategoryByNameQuery($categoryNames, $typeNames));
		while ($row = $result->fetch())
		{
			if(!$categories->contains($row['C_ID']))
			{
				$categories->addEntity(new SpecificationCategory($row['C_ID'], $row['C_NAME'], $row['C_DISPLAY_ORDER']));
			}
			if(!$categories->getEntity($row['C_ID'])->getSpecificationList()->contain($row['T_ID']))
			{
				$categories->getEntity($row['C_ID'])
						   ->getSpecificationList()
						   ->addEntity(new Specification($row['T_ID'], $row['T_NAME'], $row['T_DISPLAY_ORDER']));
			}
		}
		return $categories;
	}



	private function getSpecificationCategoryByNameQuery(array $categoryNames, array $typeNames): string
	{
		$implodeC = implode(',', array_map(function($str) {return "'$str'";}, $categoryNames));
		$implodeT = implode(',', array_map(function($str) {return "'$str'";}, $typeNames));
		$whereInC = (empty($categoryNames) ? '' : "AND usc.ID IN ({$implodeC})");
		$whereInT = (empty($typeNames) ? '' : "AND ust.ID IN ({$implodeT})");
		return "SELECT usc.ID C_ID, usc.NAME C_NAME, usc.DISPLAY_ORDER C_DISPLAY_ORDER, ust.ID T_ID, 
                        ust.NAME T_NAME, ust.DISPLAY_ORDER T_DISPLAY_ORDER
				FROM up_spec_category usc
				INNER JOIN up_spec_type ust on usc.ID = ust.SPEC_CATEGORY_ID {$whereInC} {$whereInT};";
	}

	private function getCategoriesByItemTypeIdQuery(int $itemTypeId): string
	{
		return "SELECT 
			usc.ID as CAT_ID,
            usc.NAME as CAT_NAME,
            usc.DISPLAY_ORDER as CAT_ORDER,
            u.ID as SPEC_ID,
            u.NAME as SPEC_NAME,
            u.DISPLAY_ORDER as SPEC_ORDER
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
            usc.NAME as CAT_NAME,
            usc.DISPLAY_ORDER as CAT_ORDER,
            ust.ID as SPEC_ID,
            ust.NAME as SPEC_NAME,
            ust.DISPLAY_ORDER as SPEC_ORDER,
			uis.VALUE as SPEC_VALUE
		FROM up_item_spec uis
		INNER JOIN up_spec_type ust on uis.SPEC_TYPE_ID = ust.ID
		INNER JOIN up_spec_category usc on ust.SPEC_CATEGORY_ID = usc.ID
		WHERE uis.ITEM_ID={$itemId}";
	}

	private function createSpecificationByRow(array $row): Specification
	{
		return new Specification(
			$row['SPEC_ID'], $row['SPEC_NAME'], $row['SPEC_ORDER'], $row['SPEC_VALUE']
		);
	}
}