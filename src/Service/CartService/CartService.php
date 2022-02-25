<?php

namespace Up\Service\CartService;

use InvalidArgumentException;
use Up\DAO\ItemDAO\ItemDAOInterface;
use Up\Entity\Item;
use Up\Service\ItemService\ItemServiceInterface;

class CartService implements CartServiceInterface
{
	private const CART_SESSION_KEY = 'Cart';

	public $itemService;
	public $itemDAO;

	/**
	 * @param \Up\Service\ItemService\ItemService $itemService
	 * @param \Up\DAO\ItemDAO\ItemDAOmysql $itemDAO
	 */
	public function __construct(ItemServiceInterface $itemService, ItemDAOInterface $itemDAO)
	{
		$this->itemService = $itemService;
		$this->itemDAO = $itemDAO;
		$this->createCartInSession();
	}

	/**
	 * @param int $itemId
	 *
	 * @return void
	 * @throws InvalidArgumentException
	 */
	public function addItemToCartById(int $itemId): void
	{
		if (!$this->itemService->isItemAvailable($itemId))
		{
			throw new InvalidArgumentException('Item with this id is unavailable');
		}
		if (!in_array($itemId, $_SESSION[static::CART_SESSION_KEY], true))
		{
			$_SESSION[static::CART_SESSION_KEY][] = $itemId;
		}
	}

	public function deleteItemFromCart(int $itemId)
	{
		if (in_array($itemId, $_SESSION[static::CART_SESSION_KEY], true))
		{
			$key = array_search($itemId, $_SESSION[static::CART_SESSION_KEY], true);
			unset($_SESSION[static::CART_SESSION_KEY][$key]);
		}
	}

	public function clearCart()
	{
		unset($_SESSION[static::CART_SESSION_KEY]);
	}

	/**
	 * @return Item[]
	 */
	public function getItemsFromCart(): array
	{
		return $this->itemDAO->getItemsWithIds($this->getItemsIdsFromCart());
	}

	public function isItemInCart(int $itemId): bool
	{
		return in_array($itemId, $this->getItemsIdsFromCart(), true);
	}

	/**
	 * @return int[]
	 */
	private function getItemsIdsFromCart(): array
	{
		return $_SESSION[static::CART_SESSION_KEY];
	}

	private function createCartInSession(): void
	{
		if (!array_key_exists(static::CART_SESSION_KEY, $_SESSION))
		{
			$_SESSION[self::CART_SESSION_KEY] = [];
		}
	}
}