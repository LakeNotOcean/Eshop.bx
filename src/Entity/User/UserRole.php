<?php

namespace Up\Entity\User;

class UserRole
{
	protected $name;
	protected $id;

	public function __construct(UserEnum $name = null)
	{
		if (is_null($name))
		{
			$this->name = UserEnum::Guest();
			$this->id = 0;
		}
		else
		{
			$this->id = self::getIdByName($name->getValue());
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
	 * @return UserEnum
	 */
	public function getName(): UserEnum
	{
		return $this->name;
	}

	private static function getIdByName(string $name): int
	{
		switch ($name)
		{
			case UserEnum::User:
				return 2;
			case UserEnum::Admin:
				return 1;
			case UserEnum::Moderator:
				return 3;
			default:
				return 0;
		}
	}

}
