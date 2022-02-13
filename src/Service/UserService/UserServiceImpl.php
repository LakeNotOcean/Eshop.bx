<?php

namespace Up\Service\UserService;

use Exception;
use Up\DAO\UserDAO\UserDAO;
use Up\Entity\User;
use Up\Entity\UserRole;


class UserServiceImpl implements UserService
{
	private const Admin = 'Admin';
	private const UserKey = 'USER';

	protected $userDAO;

	/**
	 * @param \Up\DAO\UserDAO\UserDAOmysql $userDAO
	 */
	public function __construct(UserDAO $userDAO)
	{
		$this->userDAO = $userDAO;
	}

	/**
	 * @throws Exception
	 */
	public function authorizeUserByLogin(string $login, string $password): void
	{
		if (!$this->userDAO->authenticateUser($login, $password))
		{
			throw new Exception('Неверный логин и/или пароль ');
		}
		$user = $this->userDAO->getUserByLogin($login);
		$this->addUserToSession($user);
	}

	/**
	 * @throws Exception
	 */
	public function giveUserModeratorRights(string $login)
	{
		$this->checkIsAdmin();
		$this->userDAO->giveUserModeratorRoleByLogin($login);

	}

	/**
	 * @throws Exception
	 */
	public function removeUserModeratorRights(string $login)
	{
		$this->checkIsAdmin();
		$this->userDAO->removeUserModeratorRoleByLogin($login);
	}

	public function getUserInfo(): User
	{
		$this->startSessionIfNotExists();
		if (!isset($_SESSION[self::UserKey]))
		{
			$_SESSION[self::UserKey] = new User('', new UserRole());
		}

		return $_SESSION[self::UserKey];
	}

	public function registerUser(user $user, string $password): void
	{
		$this->userDAO->addUser($user, $password);
		$this->addUserToSession($user);
	}

	private function addUserToSession(User $user): void
	{
		$this->startSessionIfNotExists();
		$_SESSION[self::UserKey] = $user;
	}

	private function startSessionIfNotExists(): void
	{
		if (session_status() === PHP_SESSION_NONE)
		{
			session_start();
		}
	}

	/**
	 * @throws Exception
	 */
	public function checkIsAdmin(): void
	{
		if ($this->getUserInfo()->getRole()->getName() !== self::Admin)
		{
			throw new Exception('You are not authorized to perform the operation ');
		}
	}

	public function removeUserFromSession(): void
	{
		$this->sessionDestroy();
	}

	private function sessionDestroy(): void
	{
		session_start();
		session_unset();
		session_destroy();
		session_write_close();
		setcookie(session_name(), '', 0, '/');
	}

	public function getUsersInfo(): array
	{
		if ($this->getUserInfo()->getRole()->getName() !== self::Admin)
		{
			return $this->getUsersList();
		}

		return [];
	}

	private function getUsersList(): array
	{
		return $this->userDAO->getUsersInfo();
	}
}
