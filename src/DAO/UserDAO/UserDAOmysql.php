<?php

namespace Up\DAO\UserDAO;

use Up\Core\Database\DefaultDatabase;
use Up\Entity\User\User;
use Up\Entity\User\UserEnum;
use Up\Entity\User\UserRole;

class UserDAOmysql implements UserDAOInterface
{
	private $DBConnection;

	/**
	 * @param \Up\Core\Database\DefaultDatabase $DBConnection
	 */
	public function __construct(DefaultDatabase $DBConnection)
	{
		$this->DBConnection = $DBConnection;
	}

	public function authenticateUser(string $login, string $password): bool
	{
		$query = "SELECT
			up_user.LOGIN as USER_LOGIN,
            up_user.PASSWORD as USER_PASSWORD
		FROM up_user WHERE LOGIN='{$login}';";
		$queryResult = $this->DBConnection->prepare($query);
		$queryResult->execute();
		$resultList = [];
		while ($row = $queryResult->fetch())
		{
			$resultList[$row['USER_LOGIN']] = $row['USER_PASSWORD'];
		}

		return array_key_exists($login, $resultList) && password_verify($password, $resultList[$login]);

	}

	public function getUserByLogin(string $login): User
	{
		$userList = $this->getUsersList($login);

		return $userList[$login];
	}

	public function addUser(User $user, string $password): void
	{
		$password = password_hash($password, PASSWORD_BCRYPT);

		$query = "INSERT INTO up_user (LOGIN, EMAIL, PHONE, PASSWORD, ROLE_ID,FIRST_NAME,SECOND_NAME) 
			VALUES ('{$user->getLogin()}','{$user->getEmail()}','{$user->getPhone()}','$password','2','{$user->getFirstName()}','{$user->getSecondName()}')";

		$queryResult = $this->DBConnection->prepare($query);
		$queryResult->execute();
	}

	public function giveUserModeratorRoleByLogin(string $login): void
	{
		$this->changeRole($login, 3);
	}

	public function removeUserModeratorRoleByLogin(string $login): void
	{
		$this->changeRole($login, 2);
	}

	public function getUsersInfo(): array
	{
		return $this->getUsersList();
	}

	private function getUsersList(string $login = ''): array
	{
		$query = "SELECT
			uu.LOGIN as USER_LOGIN,
            uu.PASSWORD as USER_PASSWORD,
            uu.PHONE as USER_PHONE,
            uu.EMAIL as USER_EMAIL,
            ur.ID as ROLE_ID,
            ur.NAME as ROLE_NAME,   
            uu.FIRST_NAME as USER_FIRST_NAME,
            uu.SECOND_NAME as USER_SECOND_NAME
		FROM up_user uu
		LEFT JOIN up_role ur on ur.ID = uu.ROLE_ID";
		if ($login !== '')
		{
			$query = $query . " WHERE uu.LOGIN='{$login}'";
		}
		$queryResult = $this->DBConnection->prepare($query);
		$queryResult->execute();
		$resultList = [];
		while ($row = $queryResult->fetch())
		{
			$resultList[$row['USER_LOGIN']] = $this->createUserByRow($row);
		}

		return $resultList;
	}

	/**
	 * @throws \ReflectionException
	 * @throws \Up\Core\Enum\EnumException
	 */
	private function createUserByRow($row): User
	{
		return new User(
			$row['USER_LOGIN'],
			new UserRole(new UserEnum($row['ROLE_NAME'])),
			$row['USER_EMAIL'],
			$row['USER_PHONE'],
			$row['USER_FIRST_NAME'],
			$row['USER_SECOND_NAME']
		);
	}

	private function changeRole(string $login, int $roleId): void
	{
		$query = "UPDATE up_user SET ROLE_ID={$roleId} WHERE LOGIN={$login}";
		$queryResult = $this->DBConnection->prepare($query);
		$queryResult->execute();
	}
}
