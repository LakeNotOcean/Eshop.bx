<?php

namespace Up\Entity;

class ItemsImage extends Entity
{
	protected $width = 0;
	protected $height = 0;
	protected $path = '';
	protected $isMain = false;

	/**
	 * @return int
	 */
	public function getWidth(): int
	{
		return $this->width;
	}

	/**
	 * @param int $width
	 */
	public function setWidth(int $width): void
	{
		$this->width = $width;
	}

	/**
	 * @return int
	 */
	public function getHeight(): int
	{
		return $this->height;
	}

	/**
	 * @param int $height
	 */
	public function setHeight(int $height): void
	{
		$this->height = $height;
	}

	/**
	 * @return string
	 */
	public function getPath(): string
	{
		return $this->path;
	}

	/**
	 * @param string $path
	 */
	public function setPath(string $path): void
	{
		$this->path = $path;
	}

	/**
	 * @return bool
	 */
	public function isMain(): bool
	{
		return $this->isMain;
	}

	/**
	 * @param bool $isMain
	 */
	public function setIsMain(bool $isMain): void
	{
		$this->isMain = $isMain;
	}
}