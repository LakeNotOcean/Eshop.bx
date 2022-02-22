<?php

namespace Up\DAO\ItemDAO;

use Up\Entity\Item;
use Up\Entity\ItemDetail;


interface ItemDAOInterface
{
	public function getItems(int $offset, int $amountItems): array;

	public function getItemsByTypeID(int $offset,int $amountItems, int $typeID);

	public function getItemsByQuery(int $offset, int $amountItems, string $searchQuery): array;


	public function getSimilarItemById(int $id,int $similarAmount): array;

	public function getItemsMinMaxPrice(): array;

	public function getItemsMinMaxPriceByItemType(int $typeId): array;

	public function getItemsByFilters(int $offset, int $amountItems,string $query,string $price,array $tags,array $specs, int $typeId): array;

	public function getItemsByOrderId(int $orderId): array;

	public function getItemDetailById(int $id): ItemDetail;

	public function save(ItemDetail $item): ItemDetail;

	public function getItemsAmount(string $searchQuery = ''): int;

	public function getItemsAmountByTypeId(int $typeId,string $searchQuery = ''): int;

	public function getItemsAmountByFilters(string $query,string $price,array $tags,array $specs);

	public function deactivateItem(int $id): void;

	public function updateCommonInfo(Item $item): Item;


}
