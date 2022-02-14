<?php

namespace Up\Entity;


class ItemType extends Entity
{
	protected $name;

	public function __construct(int $id = 1, string $name = '')
	{
		$this->id = $id;
		$this->name = $name;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

}
