<?php

namespace Up\DAO\SpecificationDAO;

use Up\Core\Database\DefaultDatabase;
use Up\Entity\ItemType;
use Up\Entity\Specification;
use Up\Entity\SpecificationCategory;
use Up\Entity\SpecificationCategoryFilter;
use Up\Entity\SpecificationFilter;

class SpecificationDAOmysql implements SpecificationDAOInterface
{
	private $DBConnection;

	/**
	 * @param \Up\Core\Database\DefaultDatabase $DBConnection
	 */
	public function __construct(DefaultDatabase $DBConnection)
	{
		$this->DBConnection = $DBConnection;
	}

	public function getCategoriesWithValueByItemTypeId(int $queryTypeId): array
	{
		$result = $this->DBConnection->query($this->getCategoriesWithValueByItemTypeIdQuery($queryTypeId));
		$resultArray = [];
		while ($row = $result->fetch())
		{
			$categoryId = $row['CAT_ID'];
			if (!array_key_exists($categoryId, $resultArray))
			{
				$resultArray[$categoryId] = new SpecificationCategoryFilter(
					$row['CAT_ID'], $row['CAT_NAME'], $row['CAT_ORDER']
				);
			}
			$specId = $row['SPEC_ID'];
			$specValue = $row['SPEC_VALUE'];
			$specCount = $row['SPEC_COUNT'];
			if (!array_key_exists($specId,$resultArray[$categoryId]->getSpecificationList()->getEntitiesArray()))
			{
				$specification = new SpecificationFilter(
					$specId, $row['SPEC_NAME'], $row['SPEC_ORDER']
				);
					$specification->setValue($specValue,$specCount);
					$resultArray[$categoryId]->addToSpecificationList($specification);
			}
			else
			{
				$specification = $resultArray[$categoryId]->getSpecificationList()->getEntity($specId);
				$specification->setValue($specValue,$specCount);
			}
		}

		return $resultArray;
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
				$specId, $row['SPEC_NAME'], $row['SPEC_ORDER']
			);
			$resultArray[$categoryId]->setSpecification($specification);
		}

		return $resultArray;
	}

	public function getItemCategoriesByItemId(int $itemId): array
	{
		$categoriesList = [];
		$queryResult = $this->DBConnection->query($this->getCategoriesByItemIdQuery($itemId));
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
			if (!$categoriesList[$categoryId]->hasSpecification($specificationId))
			{
				$categoriesList[$categoryId]->setSpecification($this->createSpecificationByRow($row));
			}
		}

		return $categoriesList;

	}

	public function addItemType(string $name): void
	{
		$query = "INSERT INTO up_item_type (NAME) VALUES (?);";
		$prepare = $this->DBConnection->prepare($query);
		$prepare->execute([$name]);
	}

	public function addSpecTemplate(int $itemTypeId, array $templateSpecs): void
	{
		$query = "INSERT INTO up_spec_template (ITEM_TYPE_ID, SPEC_TYPE_ID) VALUES (?,?);";
		$prepare = $this->DBConnection->prepare($query);
		$data = $this->prepareTemplateSpecs($itemTypeId, $templateSpecs);
		foreach ($data as $row)
		{
			$prepare->execute($row);
		}
	}

	public function getTypes(): array
	{
		$queryResult = $this->DBConnection->query($this->getTypesQuery());
		$typeList = [];
		while ($row = $queryResult->fetch())
		{
			$typeList[] = $this->createTypeByRow($row);
		}

		return $typeList;
	}

	public function getItemTypeByName(string $name): ItemType
	{
		$prepare = $this->DBConnection->query($this->getItemTypeByNameQuery($name));
		$itemTypeData = $prepare->fetch();

		return new ItemType($itemTypeData['ID'], $itemTypeData['NAME']);
	}

	private function getItemTypeByNameQuery(string $name): string
	{
		return "SELECT ID, NAME FROM up_item_type WHERE NAME = '$name';";
	}

	public function getSpecificationByCategoryId(int $id): array
	{
		$result = $this->DBConnection->query($this->getSpecificationByCategoryByIdQuery($id));
		$specification = [];
		while ($row = $result->fetch())
		{
			$specification[$row['ID']] = new Specification($row['ID'], $row['NAME'], $row['DISPLAY_ORDER']);
		}

		return $specification;
	}

	public function deleteCategoryById(int $id): void
	{
		$this->DBConnection->query($this->getDeleteCategoryByIdQuery($id));
	}

	public function deleteSpecificationById(int $id): void
	{
		$this->DBConnection->query($this->getDeleteSpecificationByIdQuery($id));
	}

	private function getDeleteSpecificationByIdQuery(int $id): string
	{
		return "DELETE FROM up_spec_type WHERE ID={$id}";
	}

	private function getDeleteCategoryByIdQuery(int $id): string
	{
		return "DELETE FROM up_spec_category WHERE ID={$id}";
	}

	private function getSpecificationByCategoryByIdQuery(int $id): string
	{
		return "SELECT ID, NAME, DISPLAY_ORDER FROM up_spec_type
				WHERE SPEC_CATEGORY_ID={$id}";
	}

	public function getCategoriesByTypes(): array
	{
		$queryResult = $this->DBConnection->query($this->getCategoriesByTypesIdQuery());
		/** @var array<int,array<int,SpecificationCategory>> $categoriesList */
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
			$categoriesList[$categoryId][$specificationId]->setSpecification(
				$this->createSpecificationByRow($row)
			);
		}

		return $categoriesList;
	}

	public function getCategoriesWithSpecifications(): array
	{
		$queryResult = $this->DBConnection->query($this->getCategoriesWithSpecQuery());
		$categoriesList = [];
		while ($row = $queryResult->fetch())
		{
			$categoryId = $row['CAT_ID'];
			if (!array_key_exists($categoryId, $categoriesList))
			{
				$categoriesList[$categoryId] = $this->createCategoryByRow($row);
			}
			$categoriesList[$categoryId]->setSpecification($this->createSpecificationByRow($row));
		}

		return $categoriesList;
	}



	public function addSpecificationsToItemById(int $itemId, array $specificationsList): void
	{
		$query = "INSERT INTO `up_item-spec` (ITEM_ID, SPEC_TYPE_ID,VALUE) VALUES (?,?,?);";
		$prepared = $this->DBConnection->prepare($query);
		$data = $this->prepareSpecList($itemId, $specificationsList);
		foreach ($data as $row)
		{
			$prepared->execute($row);
		}
	}

	/**
	 * @param array<string> $categoryNames
	 * @param array<string> $typeNames
	 *
	 * @return array<int,SpecificationCategory>
	 */
	public function getSpecificationCategoryByName(array $categoryNames, array $typeNames): array
	{
		$categories = [];
		$result = $this->DBConnection->query($this->getSpecificationCategoryByNameQuery($categoryNames, $typeNames));
		while ($row = $result->fetch())
		{
			if (!isset($categories[$row['C_ID']]))
			{
				$categories[$row['C_ID']] =  new SpecificationCategory($row['C_ID'], $row['C_NAME'], $row['C_DISPLAY_ORDER']);
			}
			if (!$categories[$row['C_ID']]->hasSpecification($row['T_ID']))
			{
				$categories[$row['C_ID']]->setSpecification(
					new Specification($row['T_ID'], $row['T_NAME'], $row['T_DISPLAY_ORDER'])
				);
			}
		}
		return $categories;
	}

	private function getSpecificationCategoryByNameQuery(array $categoryNames, array $typeNames): string
	{
		$implodeC = implode(
			',',
			array_map(static function($str) {
				return "'$str'";
			}, $categoryNames)
		);
		$implodeT = implode(
			',',
			array_map(static function($str) {
				return "'$str'";
			}, $typeNames)
		);
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
		FROM `up_item-spec` uis
		INNER JOIN up_spec_type ust on uis.SPEC_TYPE_ID = ust.ID
		INNER JOIN up_spec_category usc on ust.SPEC_CATEGORY_ID = usc.ID
		WHERE uis.ITEM_ID={$itemId}";
	}

	public function addCategory(SpecificationCategory $category): void
	{
		$query = "INSERT INTO up_spec_category (NAME, DISPLAY_ORDER) VALUES (?,?);";
		$prepare = $this->DBConnection->prepare($query);
		$prepare->execute($this->prepareCategory($category));
	}

	public function addSpecification(int $categoryId, Specification $specification): void
	{
		$query = "INSERT INTO up_spec_type (NAME, SPEC_CATEGORY_ID, DISPLAY_ORDER) VALUES (?,?,?);";
		$prepair = $this->DBConnection->prepare($query);
		$prepair->execute($this->prepareSpecification($categoryId, $specification));
	}

	public function getCategories(): array
	{
		$queryResult = $this->DBConnection->query($this->getCategoriesQuery());
		$categoriesList = [];
		while ($row = $queryResult->fetch())
		{
			$categoriesList[$row['CAT_ID']] = $this->createCategoryByRow($row);
		}

		return $categoriesList;
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

	private function prepareTemplateSpecs(int $itemTypeId, array $templateSpecs): array
	{
		$result = [];
		foreach ($templateSpecs as $specId)
		{
			$result[] = [$itemTypeId, $specId];
		}

		return $result;
	}

	private function prepareSpecification(int $categoryId, Specification $specification): array
	{
		return [$specification->getName(), $categoryId, $specification->getDisplayOrder()];
	}

	private function prepareCategory(SpecificationCategory $category): array
	{
		return [$category->getName(), $category->getDisplayOrder()];
	}

	private function createCategoryByRow(array $row): SpecificationCategory
	{
		return new SpecificationCategory($row['CAT_ID'], $row['CAT_NAME'], $row['CAT_ORDER']);
	}

	private function createTypeByRow(array $row): ItemType
	{
		return new ItemType($row['TYPE_ID'], $row['TYPE_NAME']);
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

	private function getCategoriesWithValueByItemTypeIdQuery(int $itemTypeId): string
	{
		return "SELECT DISTINCT
	SPEC_CATEGORY_ID as CAT_ID,
    usc.NAME as CAT_NAME,
    usc.DISPLAY_ORDER as CAT_ORDER,
	ust.ID as SPEC_ID,
	ust.NAME as  SPEC_NAME,
	ust.DISPLAY_ORDER as SPEC_ORDER,
	`ui-s`.VALUE as SPEC_VALUE,
	COUNT(`ui-s`.VALUE) as SPEC_COUNT,
    ITEM_TYPE_ID


FROM up_item
		INNER JOIN `up_item-spec` `ui-s` on up_item.ID = `ui-s`.ITEM_ID
		INNER JOIN up_spec_type ust on `ui-s`.SPEC_TYPE_ID = ust.ID
		INNER JOIN up_spec_category usc on ust.SPEC_CATEGORY_ID = usc.ID

WHERE ITEM_TYPE_ID = {$itemTypeId}
GROUP BY SPEC_VALUE";
	}






	private function getTypesQuery(): string
	{
		return "SELECT
       upit.ID as TYPE_ID,
       upit.NAME as TYPE_NAME
		FROM up_item_type upit;";
	}

	private function getCategoriesByTypesIdQuery(): string
	{
		return "SELECT
			usc.ID as CAT_ID,
            usc.NAME as CAT_NAME,
            usc.DISPLAY_ORDER as CAT_ORDER,
            u.ID as SPEC_ID,
            u.NAME as SPEC_NAME,
            u.DISPLAY_ORDER as SPEC_ORDER,
            ust.ITEM_TYPE_ID as TYPE_ID
		FROM up_spec_template ust
		INNER JOIN up_spec_type u on ust.SPEC_TYPE_ID = u.ID
		INNER JOIN up_spec_category usc on u.SPEC_CATEGORY_ID = usc.ID;";
	}

	private function getCategoriesWithSpecQuery(): string
	{
		return "SELECT
			usc.ID as CAT_ID,
            usc.NAME as CAT_NAME,
            usc.DISPLAY_ORDER as CAT_ORDER,
            ust.ID as SPEC_ID,
            ust.NAME as SPEC_NAME,
            ust.DISPLAY_ORDER as SPEC_ORDER
		FROM up_spec_type ust
		INNER JOIN up_spec_category usc on ust.SPEC_CATEGORY_ID = usc.ID;";
	}

	private function getCategoriesQuery(): string
	{
		return "SELECT
			usc.ID as CAT_ID,
            usc.NAME as CAT_NAME,
            usc.DISPLAY_ORDER as CAT_ORDER
		FROM up_spec_category usc;";
	}
}
