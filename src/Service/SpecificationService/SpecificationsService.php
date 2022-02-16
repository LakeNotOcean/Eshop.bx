<?php

namespace Up\Service\SpecificationService;

use Exception;
use Up\DAO\SpecificationDAO\SpecificationDAOInterface;
use Up\Entity\Specification;
use Up\Entity\SpecificationCategory;


class SpecificationsService implements SpecificationsServiceInterface
{
	protected $specificationDAO;

	/**
	 * @param \Up\DAO\SpecificationDAO\SpecificationDAOmysql $specificationDAO
	 */
	public function __construct(SpecificationDAOInterface $specificationDAO)
	{
		$this->specificationDAO = $specificationDAO;
	}

	public function getItemTypes(): array
	{
		return $this->specificationDAO->getTypes();
	}

	public function getCategoriesByItemTypeId(int $id): array
	{
		return $this->specificationDAO->getCategoriesByItemTypeId($id);
	}

	public function getCategoriesWithSpecifications(): array
	{
		//$this->specificationsSort($categories);
		return $this->specificationDAO->getCategoriesWithSpecifications();
	}

	public function getSpecificationByCategoryId(int $id): array
	{
		return $this->specificationDAO->getSpecificationByCategoryId($id);
	}

	public function getItemTemplate(int $templateId): array
	{
		//$this->specificationsSort($categories);

		return $this->specificationDAO->getCategoriesByItemTypeId($templateId);
	}

	public function addItemType(string $itemTypeName, array $templateSpecs): void
	{
		$this->specificationDAO->addItemType($itemTypeName);
		$itemType = $this->specificationDAO->getItemTypeByName($itemTypeName);
		$this->specificationDAO->addSpecTemplate($itemType->getId(), $templateSpecs);
	}

	/**
	 * @throws Exception
	 */
	public function addCategory(SpecificationCategory $category): void
	{
		$categories = $this->specificationDAO->getCategories();
		foreach ($categories as $addedCat)
		{
			if ($addedCat->getName() === $category->getName())
			{
				return;
			}
		}
		$this->specificationDAO->addCategory($category);
	}

	public function addSpecification(int $categoryId, Specification $specification): void
	{
		$categories = $this->specificationDAO->getCategoriesWithSpecifications();
		foreach ($categories as $category)
		{
			$specifications = $category->getSpecifications();
			foreach ($specifications as $currSpec)
			{
				if ($currSpec->getName() === $specification->getName())
				{
					return;
				}
			}
		}
		$allCategories = $this->specificationDAO->getCategories();
		if (!array_key_exists($categoryId, $allCategories))
		{
			return;
		}
		$this->specificationDAO->addSpecification($categoryId, $specification);
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
