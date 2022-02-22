<?php

namespace Up\Service\ItemService;

use Up\Entity\Item;
use Up\Entity\ItemDetail;


interface ItemServiceInterface
{

	public function getItems(array $limitOffset): array;

	public function getFavoriteItems(int $userId, array $limitOffset): array;

	public function getFavoriteItemsAmount(int $userId): int;

	public function addToFavorites(int $userId, int $favoriteItemId): void;

	public function removeFromFavorites(int $userId, int $favoriteItemId): void;

	public function getItemsByQuery(array $limitOffset, string $searchQuery): array;

	public function getItemsByFilters(array $limitOffset, string $query = '', string $price = '', array $tags = [], array $specs = []): array;

	public function getItemById(int $id): ItemDetail;

	public function getItemsSimilarById(int $id,int $similarAmount): array;

	public function getItemsAmount(string $query = ''): int;

	public function getItemsAmountByFilters(string $query,string $price,array $tags,array $specs): int;

	public function getItemsMinMaxPrice(): array;

	public function save(ItemDetail $item): ItemDetail;

	public function deactivateItem(int $id): void;

	public function updateCommonInfo(Item $item): Item;

}
