<?php

namespace Up\Entity;

use Up\Entity\User\User;

class Review extends Entity
{
	protected $comment = '';
	protected $rating = 0;
	/**
	 * @var User
	 */
	protected $user;
	/**
	 * @var Item
	 */
	protected $item;
	/**
	 * @var \DateTimeInterface
	 */
	protected $date;


	/**
	 * @return Item|UserItem
	 */
	public function getItem(): Item
	{
		return $this->item;
	}

	/**
	 * @param Item|UserItem $item
	 */
	public function setItem(Item $item): void
	{
		$this->item = $item;
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
	 * @return int
	 */
	public function getRating(): int
	{
		return $this->rating;
	}

	/**
	 * @param int $rating
	 */
	public function setRating(int $rating): void
	{
		$this->rating = $rating;
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

	public function issetItem(): bool
	{
		return isset($this->item);
	}

	/**
	 * @return \DateTimeInterface
	 */
	public function getDate(): \DateTimeInterface
	{
		return $this->date;
	}

	/**
	 * @param \DateTimeInterface $date
	 */
	public function setDate(\DateTimeInterface $date): void
	{
		$this->date = $date;
	}
}