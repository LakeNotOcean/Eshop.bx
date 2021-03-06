<?php

namespace Up\Service\ItemService;

use Up\DAO\ItemDAO\ItemDAOInterface;
use Up\DAO\SpecificationDAO\SpecificationDAOInterface;
use Up\DAO\TagDAO\TagDAOInterface;
use Up\DAO\TypeDAO\TypeDAOInterface;
use Up\Entity\Item;
use Up\Entity\ItemDetail;
use Up\Entity\ItemType;
use Up\Entity\UserItem;
use Up\Service\ImageService\ImageServiceInterface;

class ItemService implements ItemServiceInterface
{
	protected $itemDAO;
	protected $specificationDAO;
	protected $tagDAO;
	protected $typeDAO;
	protected $imageService;

	/**
	 * @param \Up\DAO\ItemDAO\ItemDAOmysql $itemDAO
	 * @param \Up\DAO\SpecificationDAO\SpecificationDAOmysql $specificationDAO
	 * @param \Up\DAO\TagDAO\TagDAOmysql $tagDAO
	 * @param \Up\DAO\TypeDAO\TypeDAOmysql $typeDAO
	 * @param \Up\Service\ImageService\ImageService $imageService
	 */
	public function __construct(ItemDAOInterface $itemDAO, SpecificationDAOInterface $specificationDAO, TagDAOInterface $tagDAO, TypeDAOInterface $typeDAO, ImageServiceInterface $imageService)
	{
		$this->itemDAO = $itemDAO;
		$this->specificationDAO = $specificationDAO;
		$this->tagDAO = $tagDAO;
		$this->typeDAO = $typeDAO;
		$this->imageService = $imageService;
	}

	public function getItems(array $limitOffset): array
	{
		return $this->itemDAO->getItems($limitOffset['offset'], $limitOffset['amountItems']);
	}

	public function getTypes(array $limitOffset):array
	{
		return $this->typeDAO->getTypes($limitOffset['offset'], $limitOffset['amountItems']);
	}

	public function isItemAvailable(int $itemId): bool
	{
		return $this->itemDAO->isItemActive($itemId);
	}

	public function getFavoriteItems(int $userId, array $limitOffset = ['offset' => -1, 'amountItems' => 0]): array
	{
		return $this->itemDAO->getFavoriteItems($userId, $limitOffset['offset'], $limitOffset['amountItems']);
	}

	public function mapItemToUserItem(int $userId, Item $item): UserItem
	{
		$favoriteItems = $this->getFavoriteItems($userId);
		$userItem = new UserItem();
		$userItem->setItem($item);
		$isFavorite = array_key_exists($userItem->getId(), $favoriteItems);
		$userItem->setIsFavorite($isFavorite);
		return $userItem;
	}

	public function mapItemsToUserItems(int $userId, array $items): array
	{
		$favoriteItems = $this->getFavoriteItems($userId);
		$userItems = [];
		foreach ($items as $item)
		{
			$userItem = new UserItem();
			$userItem->setItem($item);
			$isFavorite = array_key_exists($userItem->getId(), $favoriteItems);
			$userItem->setIsFavorite($isFavorite);
			$userItems[$userItem->getId()] = $userItem;
		}
		return $userItems;
	}

	public function mapItemDetailsToUserItems(int $userId, array $itemDetails): array
	{
		$favoriteItems = $this->getFavoriteItems($userId);
		$userItems = [];
		foreach ($itemDetails as $itemDetail)
		{
			$userItem = new UserItem();
			$userItem->setItemDetail($itemDetail);
			$isFavorite = array_key_exists($userItem->getId(), $favoriteItems);
			$userItem->setIsFavorite($isFavorite);
			$userItems[$userItem->getId()] = $userItem;
		}
		return $userItems;
	}

	public function mapItemDetailToUserItem(int $userId, ItemDetail $itemDetail): UserItem
	{
		$favoriteItems = $this->getFavoriteItems($userId);
		$userItem = new UserItem();
		$userItem->setItemDetail($itemDetail);
		$isFavorite = array_key_exists($userItem->getId(), $favoriteItems);
		$userItem->setIsFavorite($isFavorite);
		return $userItem;
	}

	public function getFavoriteItemsAmount(int $userId): int
	{
		return $this->itemDAO->getFavoriteItemsAmount($userId);
	}

	public function getTypesAmount(): int
	{
		return $this->typeDAO->getTypesAmount();
	}

	public function addToFavorites(int $userId, int $favoriteItemId): void
	{
		$this->itemDAO->addToFavorites($userId, $favoriteItemId);
	}

	public function removeFromFavorites(int $userId, int $favoriteItemId): void
	{
		$this->itemDAO->removeFromFavorites($userId, $favoriteItemId);
	}

	public function getItemsByTypeID(array $limitOffset, int $typeID): array
	{
		return $this->itemDAO->getItemsByTypeID($limitOffset['offset'], $limitOffset['amountItems'], $typeID);
	}

	public function getFirstAvailableItemOfType(ItemType $itemType): ?Item
	{
		return $this->itemDAO->getFirstActiveItemByTypeId($itemType->getId());
	}

	public function getItemsByQuery(array $limitOffset, string $searchQuery): array
	{
		return $this->itemDAO->getItemsByQuery($limitOffset['offset'], $limitOffset['amountItems'], $searchQuery);
	}

	public function getItemsByFilters(array $limitOffset,string $query,string $price,array $tags,array $specs,int $typeId, bool $deactivateInclude, string $sortingMethod): array
	{
		return $this->itemDAO->getItemsByFilters($limitOffset['offset'], $limitOffset['amountItems'], $query, $price, $tags, $specs, $typeId, $deactivateInclude, $sortingMethod);
	}

	public function getItemsMinMaxPriceByItemTypes(int $typeIds): array
	{
		return $this->itemDAO->getItemsMinMaxPriceByItemTypes($typeIds);
	}


	public function getTypeIdByQuery(string $query):array
	{
		return $this->typeDAO->getTypeIdByQuery($query);
	}


	public function getItemById(int $id): ItemDetail
	{
		return $this->itemDAO->getItemDetailById($id);
	}

	public function getItemsSimilarById(int $id,int $similarAmount): array
	{
		return $this->itemDAO->getSimilarItemById($id, $similarAmount);
	}

	public function getFirstItemsWithType(): array
	{
		return $this->itemDAO->getFirstItemsWithType();
	}


	public function getItemsAmount(string $query = ''): int
	{
		return $this->itemDAO->getItemsAmount($query);
	}

	public function getItemsAmountByItemType(int $typeId,string $query = ''): int
	{
		return $this->itemDAO->getItemsAmountByTypeId($typeId,$query);
	}

	public function getItemsAmountByFilters(string $query,string $price,array $tags,array $specs, int $typeId,bool $deactivate_include = false): int
	{
		return $this->itemDAO->getItemsAmountByFilters($query,$price,$tags,$specs,$typeId,$deactivate_include);
	}

	public function getItemsTags(): array
	{
		return $this->tagDAO->getAllTags();
	}


	public function getItemsCategoriesByItemType(int $queryTypeId): array
	{
		return $this->specificationDAO->getCategoriesWithValueByItemTypeId($queryTypeId);
	}


	public function save(ItemDetail $item): ItemDetail
	{
		return $this->itemDAO->save($item);
	}

	public function deactivateItem(int $id): void
	{
		$this->itemDAO->deactivateItem($id);
	}

	public function activateItem(int $id): void
	{
		$this->itemDAO->activateItem($id);
	}

	public function realDeleteItem(int $id): void
	{
		$this->imageService->deleteImagesByItemId($id);
		$this->itemDAO->deleteItem($id);
	}

	public function updateCommonInfo(Item $item): Item
	{
		return $this->itemDAO->updateCommonInfo($item);
	}

	public function getPurchasedItems(int $userId, array $limitOffset): array
	{
		return $this->itemDAO->getPurchasedItems($userId, $limitOffset['offset'], $limitOffset['amountItems']);
	}

	public function getAmountPurchasedItems(int $userId): int
	{
		return $this->itemDAO->getAmountPurchasedItems($userId);
	}
}
