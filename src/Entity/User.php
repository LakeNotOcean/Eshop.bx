<?php

namespace Up\Entity;

use http\Exception;

class User
{
	protected $login;
	protected $email;
	protected $role;
	protected $phone;

	private const phoneNumberPattern="/\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|
	2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|
	4[987654310]|3[9643210]|2[70]|7|1)\d{1,14}$/";

	/**
	 * @throws \Exception
	 */
	public function __construct(string $login, UserRole $role, string $email = 'test@test.test', string $phone = '')
	{
		$this->validateEmail($email);
		$this->validatePhone($phone);
		$this->login = $login;
		$this->role = $role;

		$this->email = $email;
		$this->phone = $phone;
	}

	/**
	 * @param string $email
	 *
	 * @throws \Exception
	 */
	public function setEmail(string $email): void
	{
		$this->validateEmail($email);
		$this->email = $email;
	}

	/**
	 * @param string $phone
	 *
	 * @throws \Exception
	 */
	public function setPhone(string $phone): void
	{
		$this->validatePhone($phone);
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

	/**
	 * @throws \Exception
	 */
	private function validateEmail(string $email)
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			throw new \Exception('Invalid email format');
		}
	}

	/**
	 * @throws \Exception
	 */
	private function validatePhone(string $phone)
	{
		if (!preg_match(self::phoneNumberPattern,$phone))
		{
			throw new \Exception('Invalid phone format');
		}
	}

}