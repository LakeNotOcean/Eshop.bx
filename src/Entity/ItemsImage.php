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
	 * @param string|null $extension
	 *
	 * @return string
	 */
	public function getPath(string $size, string $extension = null): string
	{
		if (is_null($extension))
		{
			return $this->paths[$size];
		}
		return $this->paths[$size] . '.' . $extension;
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
	 * @return array<string>
	 */
	public function getSizes(): array
	{
		return array_keys($this->paths);
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
