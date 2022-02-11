<?php

namespace Up\Entity;

class User
{
	protected $login;
	protected $email;
	protected $role;
	protected $phone;
	protected $secondName;
	protected $firstName;

	public function __construct(
		string   $login,
		UserRole $role,
		string   $email = '',
		string   $phone = '',
		string   $firstName = '',
		string   $secondName = ''
	)
	{
		$this->login = $login;
		$this->role = $role;
		$this->email = $email;
		$this->phone = $phone;
	}

	/**
	 * @return mixed
	 */
	public function getSecondName()
	{
		return $this->secondName;
	}

	/**
	 * @param mixed $secondName
	 */
	public function setSecondName($secondName): void
	{
		$this->secondName = $secondName;
	}

	/**
	 * @return mixed
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}

	/**
	 * @param mixed $firstName
	 */
	public function setFirstName($firstName): void
	{
		$this->firstName = $firstName;
	}

	/**
	 * @param string $email
	 */
	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	/**
	 * @param string $phone
	 */
	public function setPhone(string $phone): void
	{
		$this->phone = $phone;
	}

	/**
	 * @return string
	 */
	public function getLogin(): string
	{
		return $this->login;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @return UserRole
	 */
	public function getRole(): UserRole
	{
		return $this->role;
	}

	/**
	 * @return string
	 */
	public function getPhone(): string
	{
		return $this->phone;
	}
}