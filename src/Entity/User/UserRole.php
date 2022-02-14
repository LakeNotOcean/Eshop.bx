<?php

namespace Up\Entity\User;


class UserRole
{
	protected $id;
	protected $name;

	public function __construct(int $id = 0, UserEnum $name = null)
	{
		$this->id = $id;
		if (is_null($name))
		{
			$name=UserEnum::Guest;
		}
		else
		{
			$this->name = $name;
		}

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
		return $this->name->getValue();
	}

}
