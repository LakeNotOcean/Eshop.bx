<?php

namespace Up\DAO\SpecificationDAO;

use Up\Entity\ItemType;
use Up\Entity\Specification;
use Up\Entity\SpecificationCategory;


interface SpecificationDAOInterface
{
	public function getCategoriesByItemTypeId(int $itemTypeId): array;

	public function getItemCategoriesByItemId(int $itemId): array;

	public function addSpecificationsToItemById(int $itemId, array $specificationsList): void;

	public function getCategoriesWithSpecifications(): array;

	public function getCategoriesByTypes(): array;

	public function addItemType(string $name): void;

	public function addSpecTemplate(int $itemTypeId, array $templateSpecs): void;

	public function getTypes(): array;

	public function getItemTypeByName(string $name): ItemType;

	public function addCategory(SpecificationCategory $category): void;

	public function getCategories(): array;

	public function addSpecification(int $categoryId, Specification $specification): void;

	public function getSpecificationByCategoryId(int $id): array;

	public function deleteCategoryById(int $id): void;

	public function deleteSpecificationById(int $id): void;

}
