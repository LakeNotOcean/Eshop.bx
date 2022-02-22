<?php

namespace Up\Service\ItemService;

use Up\DAO\ItemDAO\ItemDAOInterface;
use Up\DAO\SpecificationDAO\SpecificationDAOInterface;
use Up\DAO\TagDAO\TagDAOInterface;
use Up\DAO\TypeDAO\TypeDAOInterface;
use Up\Entity\EntityArray;
use Up\Entity\Item;
use Up\Entity\ItemDetail;


class ItemService implements ItemServiceInterface
{
	protected $itemDAO;
	protected $specificationDAO;
	protected $tagDAO;
	protected $typeDAO;

	/**
	 * @param \Up\DAO\ItemDAO\ItemDAOmysql $itemDAO
	 * @param \Up\DAO\SpecificationDAO\SpecificationDAOmysql $specificationDAO
	 * @param \Up\DAO\TagDAO\TagDAOmysql $tagDAO
	 * @param \Up\DAO\TypeDAO\TypeDAOmysql $typeDAO
	 */
	public function __construct(ItemDAOInterface $itemDAO, SpecificationDAOInterface $specificationDAO, TagDAOInterface $tagDAO, TypeDAOInterface $typeDAO)
	{
		$this->itemDAO = $itemDAO;
		$this->specificationDAO = $specificationDAO;
		$this->tagDAO = $tagDAO;
		$this->typeDAO = $typeDAO;
	}


	public function getItems(array $limitOffset): array
	{
		return $this->itemDAO->getItems($limitOffset['offset'], $limitOffset['amountItems']);
	}

	public function getItemsByTypeID(array $limitOffset, int $typeID): array
	{
		return $this->itemDAO->getItemsByTypeID($limitOffset['offset'], $limitOffset['amountItems'], $typeID);
	}



	public function getItemsByQuery(array $limitOffset, string $searchQuery): array
	{
		return $this->itemDAO->getItemsByQuery($limitOffset['offset'], $limitOffset['amountItems'], $searchQuery);
	}

	public function getItemsByFilters(array $limitOffset,string $query,string $price,array $tags,array $specs,int $typeId): array
	{
		return $this->itemDAO->getItemsByFilters($limitOffset['offset'], $limitOffset['amountItems'],$query, $price, $tags,$specs,$typeId);
	}

	public function getItemsMinMaxPriceByItemType(int $typeID): array
	{
		return $this->itemDAO->getItemsMinMaxPriceByItemType($typeID);
	}

	public function getItemsMinMaxPrice():array
	{
		return $this->itemDAO->getItemsMinMaxPrice();
	}

	public function getTypeIdByQuery(string $query):array
	{
		return $this->typeDAO->getTypeIdByQuery($query);
	}

	public function getItemsByPrice(array $price):array
	{
		return $this->itemDAO->getItemsByPrice($price);
	}

	public function getItemById(int $id): ItemDetail
	{
		//$itemCategories = $this->specificationDAO->getItemCategoriesByItem($item);
		//$this->specificationsSort($itemCategories);
		//$item->setSpecificationCategoryList($itemCategories);
		return $this->itemDAO->getItemDetailById($id);
	}

	public function getItemsSimilarById(int $id,int $similarAmount): array
	{
		return $this->itemDAO->getSimilarItemById($id, $similarAmount);
	}


	public function getItemsAmount(string $query = ''): int
	{
		return $this->itemDAO->getItemsAmount($query);
	}

	public function getItemsAmountByItemType(int $typeId,string $query = ''): int
	{
		return $this->itemDAO->getItemsAmountByTypeId($typeId,$query);
	}

	public function getItemsAmountByFilters(string $query,string $price,array $tags,array $specs):int
	{
		return $this->itemDAO->getItemsAmountByFilters($query,$price,$tags,$specs);
	}



	public function getItemsCategoriesByItemType(int $typeID = 1): array
	{
		return $this->specificationDAO->getCategoriesWithValueByItemTypeId($typeID);
	}


	public function save(ItemDetail $item): ItemDetail
	{
		return $this->itemDAO->save($item);
	}

	public function deactivateItem(int $id): void
	{
		$this->itemDAO->deactivateItem($id);
	}

	public function updateCommonInfo(Item $item): Item
	{
		return $this->itemDAO->updateCommonInfo($item);
	}
}
