<?php

namespace Up\Core\Entity;

class Item
{
	protected $id = 0;
	protected $title = '';
	protected $price = 0;
	protected $shortDescription = '';
	protected $sortOrder = 0;
	protected $isActive = 0;

	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle(string $title): void
	{
		$this->title = $title;
	}

	/**
	 * @return int
	 */
	public function getPrice(): int
	{
		return $this->price;
	}

	/**
	 * @param int $price
	 */
	public function setPrice(int $price): void
	{
		$this->price = $price;
	}

	/**
	 * @return string
	 */
	public function getShortDescription(): string
	{
		return $this->shortDescription;
	}

	/**
	 * @param string $shortDescription
	 */
	public function setShortDescription(string $shortDescription): void
	{
		$this->shortDescription = $shortDescription;
	}

	/**
	 * @return int
	 */
	public function getSortOrder(): int
	{
		return $this->sortOrder;
	}

	/**
	 * @param int $sortOrder
	 */
	public function setSortOrder(int $sortOrder): void
	{
		$this->sortOrder = $sortOrder;
	}

	/**
	 * @return int
	 */
	public function getIsActive(): int
	{
		return $this->isActive;
	}

	/**
	 * @param int $isActive
	 */
	public function setIsActive(int $isActive): void
	{
		$this->isActive = $isActive;
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

}