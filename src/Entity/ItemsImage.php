<?php

namespace Up\Entity;


class ItemsImage extends Entity
{
	protected $originalImagePath;
	protected $paths;
	protected $isMain;

	public function __construct(int $id = 0, array $paths = [], bool $isMain = false, string $originalImagePath = '')
	{
		$this->id = $id;
		$this->paths = $paths;
		$this->isMain = $isMain;
		$this->originalImagePath = $originalImagePath;
	}

	/**
	 * @param string $size
	 *
	 * @return string
	 */
	public function getPath(string $size): string
	{
		return $this->paths[$size];
	}

	/**
	 * @param string $path
	 * @param string $size
	 */
	public function setPath(string $size, string $path): void
	{
		$this->paths[$size] = $path;
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

	/**
	 * @return string
	 */
	public function getOriginalImagePath(): string
	{
		return $this->originalImagePath;
	}

	/**
	 * @param string $originalImagePath
	 */
	public function setOriginalImagePath(string $originalImagePath): void
	{
		$this->originalImagePath = $originalImagePath;
	}
}
