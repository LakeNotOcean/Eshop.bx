<?php

namespace Up\Service;

use Up\DAO\ItemDAO;
use Up\DAO\SpecificationDAO;
use Up\Entity\ItemDetail;

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
		//$itemCategories = $this->specificationDAO->getItemCategoriesByItem($item);
		//$this->specificationsSort($itemCategories);
		//$item->setSpecificationCategoryList($itemCategories);
		return $item;
	}

	public function getResultCount(): int
	{
		return 2;
	}

	private function specificationsSort(array &$categories):void
	{
		usort($categories, function($a,$b)
		{
			return $a->getDisplayOrder()<=>$b->getDisplayOrder();
		});
		foreach ($categories as $id=>&$category)
		{
			$category->specificationsSort();
		}
	}

}
