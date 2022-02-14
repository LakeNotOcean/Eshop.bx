<?php

namespace Up\Entity;


class UserRole
{
	protected $id;
	protected $name;

	public function __construct(int $id = 0, string $name = "Guest")
	{
		$this->id = $id;
		$this->name = $name;
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

}
