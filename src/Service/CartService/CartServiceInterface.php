<?php

namespace Up\Service\CartService;

use Up\Entity\Item;

interface CartServiceInterface
{
	public function addItemToCartById(int $itemId): void;

	public function deleteItemFromCart(int $itemId): void;

	public function clearCart(): void;

	public function getItemsFromCart(): array;

	public function isItemInCart(int $itemId): bool;
}