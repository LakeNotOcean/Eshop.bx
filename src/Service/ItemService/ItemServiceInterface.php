<?php

namespace Up\Service\ItemService;

use Up\Entity\Item;
use Up\Entity\ItemDetail;
use Up\Entity\UserItem;

interface ItemServiceInterface
{

	public function getItems(array $limitOffset): array;

	public function getItemsByTypeID(array $limitOffset, int $typeID): array;

	public function getFavoriteItems(int $userId, array $limitOffset): array;

	public function mapItemsToUserItems(int $userId, array $items): array;

	public function mapItemDetailToUserItem(int $userId, ItemDetail $itemDetail): UserItem;

	public function mapItemDetailsToUserItems(int $userId, array $itemDetails): array;

	public function getFavoriteItemsAmount(int $userId): int;

	public function addToFavorites(int $userId, int $favoriteItemId): void;

	public function removeFromFavorites(int $userId, int $favoriteItemId): void;

	public function getItemsByQuery(array $limitOffset, string $searchQuery): array;

	public function getItemsByFilters(array $limitOffset,string $query,string $price,array $tags,array $specs,int $typeId, bool $deactivateInclude): array;

	public function getItemById(int $id): ItemDetail;

	public function getItemsSimilarById(int $id,int $similarAmount): array;

	public function getItemsAmount(string $query = ''): int;

	public function getItemsAmountByFilters(string $query,string $price,array $tags,array $specs, int $typeId,bool $deactivate_include = false): int;

	public function getItemsMinMaxPrice(): array;

	public function save(ItemDetail $item): ItemDetail;

	public function isItemAvailable(int $itemId): bool;

	public function deactivateItem(int $id): void;

	public function activateItem(int $id): void;

	public function updateCommonInfo(Item $item): Item;

	public function realDeleteItem(int $id): void;

}
