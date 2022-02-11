<?php

namespace Up\Service\SpecificationService;

use Up\Entity\Specification;
use Up\Entity\SpecificationCategory;

interface SpecificationsService
{
	public function getItemTypes(): array;

	public function getCategoriesWithSpecifications(): array;

	public function getCategories(): array;

	public function getItemTemplate(int $templateId): array;

	//public function specificationsSort(array &$categories): void;

	public function addCategory(SpecificationCategory $category): void;

	public function addSpecification(int $categoryId,Specification $specification): void;

	public function getSpecificationByCategoryId(int $id): array;
}
