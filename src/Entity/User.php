<?php

namespace Up\Entity;

class User
{
	protected $login;
	protected $email;
	protected $role;
	protected $phone;

	public function __construct(string $login,UserRole $role,string $email, string $phone)
	{
		$this->login=$login;
		$this->role=$role;
		$this->email=$email;
		$this->phone=$phone;
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