<?php

namespace Up\Service\SpecificationService;

use http\Exception;
use Up\DAO\SpecificationDAO\SpecificationDAO;
use Up\Entity\Specification;
use Up\Entity\SpecificationCategory;

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

	public function getCategoriesWithSpecifications(): array
	{
		$categories = $this->specificationDAO->getCategoriesWithSpecifications();
		//$this->specificationsSort($categories);
		return $categories;
	}

	public function getSpecificationByCategoryId(int $id): array
	{
		return $this->specificationDAO->getSpecificationByCategoryId($id);
	}

	public function getItemTemplate(int $templateId): array
	{
		$categories = $this->specificationDAO->getCategoriesByItemTypeId($templateId);
		//$this->specificationsSort($categories);

		return $categories;
	}

	/**
	 * @throws \Exception
	 */
	public function addCategory(SpecificationCategory $category): void
	{
		$categories = $this->specificationDAO->getCategories();
		foreach ($categories as $addedCat)
		{
			if ($addedCat->getName() === $category->getName())
			{
				throw new \Exception('This category already exists');
			}
		}
		$this->specificationDAO->addCategory($category);
	}

	/**
	 * @throws \Exception
	 */
	public function addSpecification(int $categoryId, Specification $specification): void
	{
		$categories=$this->specificationDAO->getCategoriesWithSpecifications();
		foreach ($categories as $category)
		{
			$specifications=$category->getSpecificationList();
			foreach ($specifications as $currSpec)
			{
				if ($currSpec->getName()===$specification->getName() || $currSpec->getId()===$specification->getId())
				{
					throw new \Exception('This specification already exists');
				}
			}
		}
		if (!array_key_exists($categoryId,$categories))
		{
			throw new \Exception('category does not exists');
		}
		$this->specificationDAO->addSpecification($categoryId,$specification);
	}

	public function getCategories(): array
	{
		return $this->specificationDAO->getCategories();
	}

	// public function specificationsSort(array &$categories): void
	// {
	// 	usort($categories, function($a, $b) {
	// 		return $a->getDisplayOrder() <=> $b->getDisplayOrder();
	// 	});
	// 	foreach ($categories as $id => &$category)
	// 	{
	// 		$category->specificationsSort();
	// 	}
	// }


}
