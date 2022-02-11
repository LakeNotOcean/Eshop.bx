<?php

namespace Up\Service\ItemService;

use Up\DAO\ItemDAO\ItemDAO;
use Up\DAO\SpecificationDAO\SpecificationDAO;
use Up\Entity\ItemDetail;
use Up\Entity\ItemsImage;

class ItemService implements ItemServiceInterface
{
	protected $itemDAO;
	protected $specificationDAO;

	public function __construct(ItemDAO $itemDAO, SpecificationDAO $specificationDAO)
	{
		$this->itemDAO = $itemDAO;
		$this->specificationDAO = $specificationDAO;

	}

	public function getItems(array $limitOffset): array
	{
		$items = $this->itemDAO->getItems($limitOffset['offset'], $limitOffset['amountItems']);

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

	public function getItemsAmount(): int
	{
		return $this->itemDAO->getItemsAmount();
	}

	public function save(ItemDetail $item): ItemDetail
	{
		return $this->itemDAO->save($item);
	}
}
