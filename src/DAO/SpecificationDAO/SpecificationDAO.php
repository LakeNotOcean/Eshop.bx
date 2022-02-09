<?php

namespace Up\DAO\SpecificationDAO;

use Up\Entity\Specification;
use Up\Entity\SpecificationCategory;

interface SpecificationDAO
{
	public function getCategoriesByItemTypeId(int $itemTypeId): array;

	public function getItemCategoriesByItemId(int $itemId): array;

	public function addSpecificationsToItemById(int $itemId, array $specificationsList): void;

	public function getCategoriesWithSpecifications(): array;

	public function getCategoriesByTypes(): array;

	public function getTypes(): array;

	public function addCategory(SpecificationCategory $category):void;

	public function getCategories():array;

	public function addSpecification(int $categoryId, Specification $specification):void;

}