<?php

namespace Up\DAO\SpecificationDAO;


interface SpecificationDAO
{
	public function getCategoriesByItemTypeId(int $itemTypeId): array;

	public function getItemCategoriesByItemId(int $itemId): array;

	public function getTypes(): array;

	public function getCategoriesByTypes(): array;

	public function getCategories(): array;

	public function addSpecificationsToItemById(int $itemId,array $specificationsList):void;

}