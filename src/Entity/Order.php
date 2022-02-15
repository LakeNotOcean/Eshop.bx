<?php

namespace Up\Entity;

use Up\Entity\User\User;


class Order extends Entity
{
	protected $customer_name;
	protected $phone;
	protected $email;
	protected $comment;
	protected $items;
	protected $status;
	protected $dateCreate;
	protected $dateUpdate;
	protected $user;

	public function __construct(string $customer_name, string $phone, string $email, string $comment)
	{
		$this->customer_name = $customer_name;
		$this->phone = $phone;
		$this->email = $email;
		$this->comment = $comment;
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
	 * @return array<ItemDetail>
	 */
	public function getItems(): array
	{
		return $this->items;
	}

	/**
	 * @param array<ItemDetail> $items
	 */
	public function setItems(array $items): void
	{
		$this->items = $items;
	}

	/**
	 * @return string
	 */
	public function getStatus(): string
	{
		return $this->status;
	}

	/**
	 * @param string $status
	 */
	public function setStatus(string $status): void
	{
		$this->status = $status;
	}

	/**
	 * @return string
	 */
	public function getDateCreate(): string
	{
		return $this->dateCreate;
	}

	/**
	 * @param string $dateCreate
	 */
	public function setDateCreate(string $dateCreate): void
	{
		$this->dateCreate = $dateCreate;
	}

	/**
	 * @return string
	 */
	public function getDateUpdate(): string
	{
		return $this->dateUpdate;
	}

	/**
	 * @param string $dateUpdate
	 */
	public function setDateUpdate(string $dateUpdate): void
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

}
