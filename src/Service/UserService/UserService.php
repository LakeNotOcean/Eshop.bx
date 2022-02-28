<?php

namespace Up\Service\UserService;

use Up\DAO\UserDAO\UserDAOInterface;
use Up\Entity\User\User;
use Up\Entity\User\UserEnum;
use Up\Entity\User\UserRole;
use Up\Service\UserService\Error\UserServiceException;
use Up\Validator\DataTypes;
use Up\Validator\Validator;
use Up\Validator\ValidationException;

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
	 * @throws UserServiceException
	 */
	public function authorizeUserByLogin(string $login, string $password): void
	{
		$errors = $this->validateSignIn($login, $password);
		if (!empty($errors))
		{
			throw new ValidationException('Validation failed', $errors);
		}

		if (!$this->userDAO->authenticateUser($login, $password))
		{
			throw new UserServiceException('Неверный логин и/или пароль ', ['login' => ['Неверный логин и/или пароль'], 'password' => []]);
		}
		$user = $this->userDAO->getUserByLogin($login);
		$this->addUserToSession($user);
	}

	private function validateSignIn(string $login, string $password): array
	{
		$fields = [
			'login' => [$login, DataTypes::login(), "Ошибка в логине: "],
			'password' => [$password, DataTypes::password(), "Ошибка в пароле: "],
		];
		$result = [];
		foreach ($fields as $field => $vaildatorInfo)
		{
			$validateErrors = Validator::validate($vaildatorInfo[0], $vaildatorInfo[1]);
			foreach ($validateErrors as $error)
			{
				$result[$field][] = $vaildatorInfo[2] . $error;
			}
		}
		return $result;
	}

	/**
	 * @throws UserServiceException
	 */
	public function giveUserModeratorRights(string $login)
	{
		$this->checkIsAdmin();
		$this->userDAO->giveUserModeratorRoleByLogin($login);

	}

	/**
	 * @throws UserServiceException
	 */
	public function giveUserAdministratorRoleByLogin(string $login)
	{
		$this->checkIsAdmin();
		$this->userDAO->giveUserAdministratorRoleByLogin($login);
	}

	/**
	 * @throws UserServiceException
	 */
	public function removeUserModeratorRights(string $login)
	{
		$this->checkIsAdmin();
		$this->userDAO->removeUserModeratorRoleByLogin($login);
	}

	public function changeUserRoleByLogin(string $login, int $roleId)
	{
		$this->checkIsAdmin();
		$this->userDAO->changeUserRoleByLogin($login,$roleId);
	}

	/**
	 * @throws UserServiceException
	 */
	public function getUserListByRole(int $roleId): array
	{
		$this->checkIsAdmin();
		return $this->userDAO->getUserListByRole($roleId);
	}

	/**
	 * @throws UserServiceException
	 */
	public function getAmountUserByQuery(int $roleId,string $query): int
	{
		$this->checkIsAdmin();
		return $this->userDAO->getAmountUserByQuery($roleId,$query);
	}

	/**
	 * @throws UserServiceException
	 */
	public function getUserListByQuery(array $limitOffset,int $roleId,string $query):array
	{
		$this->checkIsAdmin();
		return $this->userDAO->getUserListByQuery($limitOffset['offset'], $limitOffset['amountItems'],$roleId, $query);
	}

	/**
	 * @throws UserServiceException
	 */
	public function getAllRoles(): array
	{
		$this->checkIsAdmin();
		return $this->userDAO->getAllRoles();
	}

	public function registerUser(User $user, string $password): User
	{
		$errors = $this->validateUserInfo($user, $password);
		if (!empty($errors))
		{
			throw new ValidationException('Validation failed', $errors);
		}
		$newUser = $this->userDAO->addUser($user, $password);
		$this->addUserToSession($newUser);
		return $newUser;
	}

	private function validateUserInfo(User $user, string $password): array
	{
		$fields = [
			'email' => [$user->getEmail(), DataTypes::email(), "Ошибка в email: "],
			'phone' => [$user->getPhone() ,DataTypes::phone(), "Ошибка в телефоне: "],
			'login' => [$user->getLogin(), DataTypes::login(), "Ошибка в логине: "],
			'password' => [$password, DataTypes::password(), "Ошибка в пароле: "],
			'firstName' => [$user->getFirstName(), DataTypes::names(), "Ошибка в имени: "],
			'secondName' => [$user->getSecondName(), DataTypes::names(), "Ошибка в фамилии: "],
		];
		$result = [];
		foreach ($fields as $field => $vaildatorInfo)
		{
			$validateErrors = Validator::validate($vaildatorInfo[0], $vaildatorInfo[1]);
			foreach ($validateErrors as $error)
			{
				$result[$field][] = $vaildatorInfo[2] . $error;
			}
		}
		$notUnique = $this->userDAO->checkUniqueFields($user);
		if ($notUnique['login'])
		{
			$result['login'][] = "Ошибка в логине: Пользователь с таким логином уже существует";
		}
		if($notUnique['email'])
		{
			$result['email'][] = "Ошибка в email: Пользователь с таким email уже существует";
		}
		if($notUnique['phone'])
		{
			$result['phone'][] = "Ошибка в телефоне: Пользователь с таким номером телефона уже существует";
		}
		return $result;
	}

	public function updateUser(User $user): void
	{
		$this->userDAO->updateUser($user);
	}

	/**
	 * @throws UserServiceException
	 */
	public function checkIsAdmin(): void
	{
		if ($this->getUserInfo()->getRole()->getName() != UserEnum::Admin())
		{
			throw new UserServiceException('You are not authorized to perform the operation ');
		}
	}

	public function removeUserFromSession(): void
	{
		$this->sessionDestroy();
	}

	public function isAuthenticated(): bool
	{
		return $this->getUserInfo()->getRole()->getName() != UserEnum::Guest();
	}

	public function getUserInfo(): User
	{
		$this->startSessionIfNotExists();
		if (!isset($_SESSION[self::UserSessionKey]))
		{
			$_SESSION[self::UserSessionKey] = new User(0,'', new UserRole());
		}

		return $_SESSION[self::UserSessionKey];
	}

	public function getUsersInfo(): array
	{
		if ($this->getUserInfo()->getRole()->getName() != UserEnum::Admin())
		{
			return $this->getUsersList();
		}

		return [];
	}

	/**
	 * @param int $id
	 * @return User
	 * @throws UserServiceException
	 * @throws \ReflectionException
	 * @throws \Up\Core\Enum\EnumException
	 */
	public function getUserInfoById(int $id): User
	{
		$this->checkIsAdmin();
		return $this->userDAO->getUserInfoById($id);
	}

	public function hasPermission(UserEnum $role): bool
	{
		$userRole = $this->getUserInfo()->getRole()->getName();
		return in_array($role->getValue(), static::userPermission[$userRole->getValue()], true);
	}

	/**
	 * @throws \ReflectionException
	 * @throws \Up\Core\Enum\EnumException
	 */
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

	public function isValidPassword(string $password, User $user): bool
	{
		return $this->userDAO->authenticateUser($user->getLogin(), $password);
	}

	public function updatePassword(string $newPassword, User $user): void
	{
		$this->userDAO->updatePassword($user, $newPassword);
	}
}
