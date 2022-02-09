<?php

namespace Up\DAO\SpecificationDAO;

use Up\Core\Database\DefaultDatabase;
use Up\Entity\Item;
use Up\Entity\ItemType;
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

		$result = $this->DBConnection->query(SpecificationDAOqueries::getCategoriesByItemTypeIdQuery($itemTypeId));
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
				$specId, $row['SPEC_NAME'],  $row['SPEC_ORDER']
			);
			$resultArray[$categoryId]->addToSpecificationList($specification);
		}

		return $resultArray;
	}

	public function getItemCategoriesByItemId(int $itemId): array
	{
		$categoriesList = [];
		$queryResult = $this->DBConnection->query(SpecificationDAOqueries::getCategoriesByItemIdQuery($itemId));
		while ($row = $queryResult->fetch())
		{
			$categoryId = $row['CAT_ID'];
			if (!array_key_exists($categoryId, $categoriesList))
			{
				$categoriesList[$categoryId] = new SpecificationCategory(
					$categoryId, $row['CAT_NAME'], $row['CAT_ORDER']
				);
			}
			$specificationId = $row['SPEC_ID'];
			if (!$categoriesList[$categoryId]->isSpecificationExist($specificationId))
			{
				$categoriesList[$categoryId]->addToSpecificationList($this->createSpecificationByRow($row));
			}
		}

		return $categoriesList;

	}

	private function createSpecificationByRow(array $row): Specification
	{
		if (empty($row['SPEC_VALUE']))
		{
			return new Specification(
				$row['SPEC_ID'], $row['SPEC_NAME'], $row['SPEC_ORDER'],
			);
		}

		return new Specification(
			$row['SPEC_ID'], $row['SPEC_NAME'], $row['SPEC_ORDER'], $row['SPEC_VALUE']
		);
	}

	private function createCategoryByRow(array $row): SpecificationCategory
	{
		return new SpecificationCategory($row['CAT_ID'], $row['CAT_NAME'], $row['CAT_ORDER']);
	}

	private function createTypeByRow(array $row): ItemType
	{
		return new ItemType($row['TYPE_ID'], $row['TYPE_NAME']);
	}

	public function getTypes(): array
	{
		$queryResult = $this->DBConnection->query(SpecificationDAOqueries::getTypesQuery());
		$typeList = [];
		while ($row = $queryResult->fetch())
		{
			$typeList[] = $this->createTypeByRow($row);
		}

		return $typeList;
	}

	public function getCategoriesByTypes(): array
	{
		$queryResult = $this->DBConnection->query(SpecificationDAOqueries::getCategoriesByTypesIdQuery());
		$categoriesList = [];
		while ($row = $queryResult->fetch())
		{
			$typeId = $row['TYPE_ID'];
			if (!array_key_exists($typeId, $categoriesList))
			{
				$categoriesList[$typeId] = [];
			}
			$categoryId = $row['SPEC_ID'];
			if (!array_key_exists($categoryId, $categoriesList[$typeId]))
			{
				$categoriesList[$typeId][$categoryId] = $this->createCategoryByRow($row);
			}
			$specificationId = $row['SPEC_ID'];
			$categoriesList[$categoryId][$specificationId]->addToSpecificationList(
				$this->createSpecificationByRow($row)
			);
		}

		return $categoriesList;
	}

	public function getCategories(): array
	{
		$queryResult = $this->DBConnection->query(SpecificationDAOqueries::getCategories());
		$categoriesList = [];
		while ($row = $queryResult->fetch())
		{
			$categoryId = $row['CAT_ID'];
			if (!array_key_exists($categoryId, $categoriesList))
			{
				$categoriesList[$categoryId] = $this->createCategoryByRow($row);
			}
			$categoriesList[$categoryId]->addToSpecificationList($this->createSpecificationByRow($row));
		}

		return $categoriesList;
	}

	public function addSpecificationsToItemById(int $itemId, array $specificationsList): void
	{
		$query = "INSERT INTO up_item_spec (ITEM_ID, SPEC_TYPE_ID,VALUE) VALUES (?,?,?);";
		$prepair = $this->DBConnection->prepare($query);
		$data = $this->prepareSpecList($itemId, $specificationsList);
		foreach ($data as $row)
		{
			$prepair->execute($row);
		}
	}

	private function prepareSpecList(int $itemId, array $specificationList): array
	{
		$result = [];
		foreach ($specificationList as $specification)
		{
			$result[] = [$itemId, $specification->getId(), $specification->getValue()];
		}

		return $result;
	}
}