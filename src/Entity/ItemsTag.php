<?php

namespace Up\Entity;


class ItemsTag extends Entity
{
	protected $name = '';

	public function __construct(int $id = 0, string $name = '')
	{
		$this->id = $id;
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}
}
