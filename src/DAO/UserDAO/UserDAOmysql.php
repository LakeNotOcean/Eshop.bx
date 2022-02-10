<?php

namespace Up\DAO\UserDAO;

use Up\Core\Database\DefaultDatabase;
use Up\Entity\User;
use Up\Entity\UserRole;

class UserDAOmysql implements UserDAO
{
	private $DBConnection;

	public function __construct(DefaultDatabase $DBConnection)
	{
		$this->DBConnection = $DBConnection;
	}

	public function authenticateUser(string $login, string $password): bool
	{
		$query = "SELECT
			up_user.LOGIN as USER_LOGIN,
            up_user.PASSWORD as USER_PASSWORD
		FROM up_user;";
		$queryResult = $this->DBConnection->query($query);
		$resultList = [];
		while ($row = $queryResult->fetch())
		{
			$resultList[$row['USER_LOGIN']] = $row['USER_PASSWORD'];
		}
		if (!array_key_exists($login, $resultList) || $password !== $resultList[$login])
		{
			return false;
		}

		return true;

	}

	public function getUserByLogin(string $login): User
	{
		$userList = $this->getUsersList($login);

		return $userList[$login];
	}

	public function addUser(User $user, string $password): void
	{
		$query = "INSERT INTO up_user (LOGIN, EMAIL, PHONE, PASSWORD, ROLE_ID) 
			VALUES ({$user->getLogin()},{$user->getEmail()},{$user->getPhone()},$password,{$user->getRole()})";
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
            ur.NAME as ROLE_NAME
		FROM up_user uu
		LEFT JOIN up_role ur on ur.ID = uu.ROLE_ID";
		if ($login !== '')
		{
			$query = $query . " WHERE uu.LOGIN=${login}";
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

	private function createUserByRow($row): User
	{
		return new User(
			$row['USER_LOGIN'], new UserRole($row['ROLE_ID'], $row['ROLE_NAME']), $row['USER_EMAIL'], $row['USER_PHONE']
		);
	}

	public function changeRole(string $login, int $roleId): void
	{
		$query = "UPDATE up_user SET ROLE_ID={$roleId} WHERE LOGIN={$login}";
		$queryResult = $this->DBConnection->prepare($query);
		$queryResult->execute();
	}
}