<?php

namespace Up\Service;

use Up\DAO\ItemDAO;
use Up\DAO\SpecificationDAO\SpecificationDAO;
use Up\Entity\ItemDetail;
use Up\Entity\Specification;

class CatalogServiceImpl implements CatalogService
{
	protected $itemDAO;
	protected $specificationDAO;

	public function __construct(ItemDAO $itemDAO, SpecificationDAO $specificationDAO)
	{
		$this->itemDAO = $itemDAO;
		$this->specificationDAO = $specificationDAO;

	}

	public function getItems(): array
	{
		$items = $this->itemDAO->getItems(0);

		return $items;
	}

	public function getItemById(int $id): ItemDetail
	{
		$item = $this->itemDAO->getItemDetailById($id);
		// $itemCategories = $this->specificationDAO->getItemCategoriesByItem($item);
		// $this->specificationsSort($itemCategories);
		// $item->setSpecificationCategoryList($itemCategories);
		$specifications = [
			new Specification(3, '123', 0, 1),
			new Specification(2, '456', '0', 23),
		];
		$this->specificationDAO->addSpecificationsToItemById(2,$specifications);

		return $item;
	}

	public function getResultCount(): int
	{
		return 2;
	}

	private function specificationsSort(array &$categories): void
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
