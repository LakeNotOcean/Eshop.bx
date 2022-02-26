<?php

namespace Up\Entity\Order;

use Up\Entity\Entity;
use Up\Entity\Item;
use Up\Entity\ItemDetail;
use Up\Entity\User\User;

class Order extends Entity
{
	protected $customer_name;
	protected $phone;
	protected $email;
	protected $comment;
	protected $items = [];
	protected $status;
	protected $dateCreate;
	protected $dateUpdate;
	protected $user;
	protected $statusNames;

	public function __construct(string $customer_name, string $phone, string $email, string $comment)
	{
		$this->customer_name = $customer_name;
		$this->phone = $phone;
		$this->email = $email;
		$this->comment = $comment;
		$this->statusNames = [
			OrderStatus::IN_PROCESSING()->getValue() => 'В обработке',
			OrderStatus::DELIVERY()->getValue() => 'Ожидает доставки',
			OrderStatus::DONE()->getValue() => 'Завершён',
			OrderStatus::CANCELLED()->getValue() => 'Отменён'
		];
	}

	/**
	 * @return string
	 */
	public function getCustomerName(): string
	{
		return $this->customer_name;
	}

	/**
	 * @param string $customer_name
	 */
	public function setCustomerName(string $customer_name): void
	{
		$this->customer_name = $customer_name;
	}

	/**
	 * @return string
	 */
	public function getPhone(): string
	{
		return $this->phone;
	}

	/**
	 * @param string $phone
	 */
	public function setPhone(string $phone): void
	{
		$this->phone = $phone;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getComment(): string
	{
		return $this->comment;
	}

	/**
	 * @param string $comment
	 */
	public function setComment(string $comment): void
	{
		$this->comment = $comment;
	}

	/**
	 * @return array<array{count: int, item: Item}>
	 */
	public function getItems(): array
	{
		return $this->items;
	}

	/**
	 * @param array<array{count: int, item: Item}> $items
	 */
	public function setItems(array $items): void
	{
		$this->items = $items;
	}

	/**
	 * @return string
	 */
	public function getStatusName(): string
	{
		return $this->statusNames[$this->status->getValue()] ?? 'undefined';
	}

	/**
	 * @return OrderStatus
	 */
	public function getStatus(): OrderStatus
	{
		return $this->status;
	}

	/**
	 * @param OrderStatus $status
	 */
	public function setStatus(OrderStatus $status): void
	{
		$this->status = $status;
	}

	/**
	 * @var \DateTimeInterface
	 */
	public function getDateCreate(): \DateTimeInterface
	{
		return $this->dateCreate;
	}

	/**
	 * @param \DateTimeInterface $dateCreate
	 */
	public function setDateCreate(\DateTimeInterface $dateCreate): void
	{
		$this->dateCreate = $dateCreate;
	}

	/**
	 * @return \DateTimeInterface
	 */
	public function getDateUpdate(): \DateTimeInterface
	{
		return $this->dateUpdate;
	}

	/**
	 * @param \DateTimeInterface $dateUpdate
	 */
	public function setDateUpdate(\DateTimeInterface $dateUpdate): void
	{
		$this->dateUpdate = $dateUpdate;
	}

	/**
	 * @return User
	 */
	public function getUser(): User
	{
		return $this->user;
	}

	/**
	 * @param User $user
	 */
	public function setUser(User $user): void
	{
		$this->user = $user;
	}

	public function getTotalCost(): int
	{
		$cost = 0;
		foreach ($this->items as $item)
		{
			$cost += $item['item']->getPrice() * $item['count'];
		}

		return $cost;
	}

	public function issetUser(): bool
	{
		return isset($this->user);
	}

	public function hasItem(int $itemId): bool
	{
		return array_key_exists($itemId, $this->items);
	}

	public function addItem(Item $item, int $count): void
	{
		$this->items[$item->getId()]['item'] = $item;
		$this->items[$item->getId()]['count'] = $count;
	}

	public function getItem(int $itemId): Item
	{
		return $this->items[$itemId]['item'];
	}

}
