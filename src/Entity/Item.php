<?php

namespace Up\Entity;


class Item extends Entity
{
	protected $title = '';
	protected $price = 0;
	protected $shortDescription = '';
	protected $sortOrder = 0;
	protected $isActive = 0;
	protected $rating = 0.0;
	protected $amountReviews = 0;
	protected $mainImage;

	/**
	 * @return float
	 */
	public function getRating(): float
	{
		return $this->rating;
	}

	/**
	 * @param float $rating
	 */
	public function setRating(float $rating): void
	{
		$this->rating = $rating;
	}

	/**
	 * @return int
	 */
	public function getAmountReviews(): int
	{
		return $this->amountReviews;
	}

	/**
	 * @param int $amountReviews
	 */
	public function setAmountReviews(int $amountReviews): void
	{
		$this->amountReviews = $amountReviews;
	}

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
	 * @return ItemsImage
	 */
	public function getMainImage(): ItemsImage
	{
		return $this->mainImage;
	}

	/**
	 * @param ItemsImage $mainImage
	 */
	public function setMainImage(ItemsImage $mainImage): void
	{
		$this->mainImage = $mainImage;
	}

	public function isSetMainImage(): bool
	{
		return isset($this->mainImage);
	}

	public function unsetMainImage(): void
	{
		unset($this->mainImage);
	}
}
