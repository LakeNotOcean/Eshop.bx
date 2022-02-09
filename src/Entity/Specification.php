<?php

namespace Up\Entity;

class Specification
{
	protected $id = 1;
	protected $name = '';
	protected $value = '';
	protected $displayOrder = 0;

	public function __construct(
		int    $id = 1,
		string $name = '',
		int    $displayOrder = 0,
		string $value = ''
	)
	{
		$this->id = $id;
		$this->name = $name;
		$this->value = $value;
		$this->displayOrder = $displayOrder;
	}

	public function getDisplayOrder(): int
	{
		return $this->displayOrder;
	}

	public function setDisplayOrder(int $displayOrder): void
	{
		$this->displayOrder = $displayOrder;
	}

	public function getValue(): string
	{
		return $this->value;
	}


	public function getId(): int
	{
		return $this->id;
	}

	public function setId(int $id): void
	{
		$this->id = $id;
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