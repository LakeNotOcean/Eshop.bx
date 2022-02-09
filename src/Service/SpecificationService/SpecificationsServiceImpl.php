<?php

namespace Up\Service\SpecificationService;

use Up\DAO\SpecificationDAO\SpecificationDAO;

class SpecificationsServiceImpl implements SpecificationsService
{

	protected $specificationDAO;

	public function __construct(SpecificationDAO $specificationDAO)
	{
		$this->specificationDAO = $specificationDAO;
	}

	public function getItemTypes(): array
	{
		return $this->specificationDAO->getTypes();
	}

	public function getCategories(): array
	{
		$categories = $this->specificationDAO->getCategories();
		$this->specificationsSort($categories);

		return $categories;
	}

	public function getItemTemplate(int $templateId): array
	{
		$categories = $this->specificationDAO->getCategoriesByItemTypeId($templateId);
		$this->specificationsSort($categories);

		return $categories;
	}

	public function specificationsSort(array &$categories): void
	{
		usort($categories, function($a, $b) {
			return $a->getDisplayOrder() <=> $b->getDisplayOrder();
		});
		foreach ($categories as $id => &$category)
		{
			$category->specificationsSort();
		}
	}
}
