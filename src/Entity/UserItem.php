<?php

namespace Up\Entity;

class UserItem extends ItemDetail
{
	protected $isFavorite;

	/**
	 * @return bool
	 */
	public function getIsFavorite(): bool
	{
		return $this->isFavorite;
	}

	/**
	 * @param bool $isFavorite
	 */
	public function setIsFavorite(bool $isFavorite): void
	{
		$this->isFavorite = $isFavorite;
	}

}
