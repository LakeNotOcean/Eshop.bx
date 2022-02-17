<?php

namespace Up\Service\UserService;

use Exception;
use Up\DAO\UserDAO\UserDAOInterface;
use Up\Entity\User\User;
use Up\Entity\User\UserEnum;
use Up\Entity\User\UserRole;
use Up\Service\UserService\Error\UserServiceException;

class UserService implements UserServiceInterface
{
	private const UserSessionKey = 'USER';
	private const userPermission = [
		UserEnum::Guest => 		[UserEnum::Guest],
		UserEnum::User => 		[UserEnum::User, UserEnum::Guest],
		UserEnum::Moderator => 	[UserEnum::Moderator, UserEnum::User, UserEnum::Guest],
		UserEnum::Admin => 		[UserEnum::Admin, UserEnum::Moderator, UserEnum::User, UserEnum::Guest]
	];

	protected $userDAO;

	/**
	 * @param \Up\DAO\UserDAO\UserDAOmysql $userDAO
	 */
	public function __construct(UserDAOInterface $userDAO)
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
			throw new UserServiceException('Неверный логин и/или пароль ');
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
		if (!isset($_SESSION[self::UserSessionKey]))
		{
			$_SESSION[self::UserSessionKey] = new User('', new UserRole());
		}

		return $_SESSION[self::UserSessionKey];
	}

	public function registerUser(user $user, string $password): void
	{
		$this->userDAO->addUser($user, $password);
		$this->addUserToSession($user);
	}

	/**
	 * @throws Exception
	 */
	public function checkIsAdmin(): void
	{
		if ($this->getUserInfo()->getRole()->getName() !== UserEnum::Admin)
		{
			throw new Exception('You are not authorized to perform the operation ');
		}
	}

	public function removeUserFromSession(): void
	{
		$this->sessionDestroy();
	}

	public function isAuthenticated()
	{
		return $this->getUserInfo()->getRole()->getName() !== UserEnum::Guest;
	}

	public function getUsersInfo(): array
	{
		if ($this->getUserInfo()->getRole()->getName() !== UserEnum::Admin)
		{
			return $this->getUsersList();
		}

		return [];
	}

	public function hasPermission(UserEnum $role)
	{
		$userRole = $this->getUserInfo()->getRole()->getName();
		return in_array($role->getValue(), static::userPermission[$userRole], true);
	}

	private function getUsersList(): array
	{
		return $this->userDAO->getUsersInfo();
	}

	private function sessionDestroy(): void
	{
		// session_start();
		session_unset();
		session_destroy();
		session_write_close();
		setcookie(session_name(), '', 0, '/');
	}

	private function addUserToSession(User $user): void
	{
		$this->startSessionIfNotExists();
		$_SESSION[self::UserSessionKey] = $user;
	}

	private function startSessionIfNotExists(): void
	{
		if (session_status() === PHP_SESSION_NONE)
		{
			session_start();
		}
	}
}
