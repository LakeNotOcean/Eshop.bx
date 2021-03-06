<?php

namespace Up\DAO\ItemDAO;

use Up\Entity\Item;
use Up\Entity\ItemDetail;


interface ItemDAOInterface
{

	public function getItems(int $offset, int $amountItems): array;

	public function getItemsByTypeID(int $offset,int $amountItems, int $typeID);

	public function getItemsByQuery(int $offset, int $amountItems, string $searchQuery): array;

	public function getFirstActiveItemByTypeId(int $typeId): ?Item;

	public function getSimilarItemById(int $id,int $similarAmount): array;

	public function getFirstItemsWithType(): array;

	public function getItemsMinMaxPriceByItemTypes(int $typeIds): array;

	public function getItemsByFilters(int $offset, int $amountItems, string $query, string $price, array $tags, array $specs, int $typeId, bool $deactivate_include, string $sortingMethod): array;

	public function getFavoriteItems(int $userId, int $offset, int $amountItems): array;

	public function getFavoriteItemsAmount(int $userId): int;

	public function addToFavorites(int $userId, int $favoriteItemId): void;

	public function removeFromFavorites(int $userId, int $favoriteItemId): void;

	public function getItemsByOrderId(int $orderId): array;

	public function getItemDetailById(int $id): ItemDetail;

	public function save(ItemDetail $item): ItemDetail;

	public function getItemsAmount(string $searchQuery = ''): int;

	public function getItemsAmountByTypeId(int $typeId,string $searchQuery = ''): int;

	public function getItemsAmountByFilters(string $query,string $price,array $tags,array $specs,int $typeId);

	public function deactivateItem(int $id): void;

	public function activateItem(int $id): void;

	public function updateCommonInfo(Item $item): Item;

	public function isItemActive(int $itemId): bool;

	/**
	 * @param int $userId
	 *
	 * @return array<Item>
	 */
	public function getPurchasedItems(int $userId, int $offset, int $amount): array;

	public function getAmountPurchasedItems(int $userId): int;

	/**
	 * @param int[] $itemIds
	 *
	 * @return Item[]
	 */
	public function getItemsWithIds(array $itemIds): array;

	public function deleteItem(int $id): void;
}
