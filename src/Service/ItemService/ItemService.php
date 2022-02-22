<?php

namespace Up\Service\ItemService;

use Up\DAO\ItemDAO\ItemDAOInterface;
use Up\DAO\SpecificationDAO\SpecificationDAOInterface;
use Up\DAO\TagDAO\TagDAOInterface;
use Up\Entity\EntityArray;
use Up\Entity\Item;
use Up\Entity\ItemDetail;


class ItemService implements ItemServiceInterface
{
	protected $itemDAO;
	protected $specificationDAO;
	protected $tagDAO;

	/**
	 * @param \Up\DAO\ItemDAO\ItemDAOmysql $itemDAO
	 * @param \Up\DAO\SpecificationDAO\SpecificationDAOmysql $specificationDAO
	 * @param \Up\DAO\TagDAO\TagDAOmysql $tagDAO
	 */
	public function __construct(ItemDAOInterface $itemDAO, SpecificationDAOInterface $specificationDAO, TagDAOInterface $tagDAO)
	{
		$this->itemDAO = $itemDAO;
		$this->specificationDAO = $specificationDAO;
		$this->tagDAO = $tagDAO;
	}

	public function getItems(array $limitOffset): array
	{
		return $this->itemDAO->getItems($limitOffset['offset'], $limitOffset['amountItems']);
	}

	public function getFavoriteItems(int $userId, array $limitOffset): array
	{
		return $this->itemDAO->getFavoriteItems($userId, $limitOffset['offset'], $limitOffset['amountItems']);
	}

	public function getFavoriteItemsAmount(int $userId): int
	{
		return $this->itemDAO->getFavoriteItemsAmount($userId);
	}

	public function addToFavorites(int $userId, int $favoriteItemId): void
	{
		$this->itemDAO->addToFavorites($userId, $favoriteItemId);
	}

	public function removeFromFavorites(int $userId, int $favoriteItemId): void
	{
		$this->itemDAO->removeFromFavorites($userId, $favoriteItemId);
	}

	public function getItemsByQuery(array $limitOffset, string $searchQuery): array
	{
		return $this->itemDAO->getItemsByQuery($limitOffset['offset'], $limitOffset['amountItems'], $searchQuery);
	}

	public function getItemsByFilters(array $limitOffset,string $query = '',string $price = '',array $tags = [],array $specs = []): array
	{
		return $this->itemDAO->getItemsByFilters($limitOffset['offset'], $limitOffset['amountItems'],$query, $price, $tags,$specs);
	}

	public function getItemsMinMaxPrice(): array
	{
		return $this->itemDAO->getItemsMinMaxPrice();
	}

	public function getItemById(int $id): ItemDetail
	{
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

	public function getItemsAmountByFilters(string $query,string $price,array $tags,array $specs): int
	{
		return $this->itemDAO->getItemsAmountByFilters($query,$price,$tags,$specs);
	}

	public function getItemsTags(): array
	{
		$tags = $this->tagDAO->getAllTags();
		return $tags->getEntitiesArray();
	}

	public function getItemsCategories(int $typeID = 1): array
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
