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
		$query = $this->DBConnection->prepare(
			'SELECT
			up_user.LOGIN as USER_LOGIN,
            up_user.PASSWORD as USER_PASSWORD
		FROM up_user WHERE LOGIN=:login;'
		);
		$query->execute(['login' => $login]);
		$resultList = [];
		while ($row = $query->fetch())
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

		$preparedQuery = $this->DBConnection->prepare(
			'INSERT INTO up_user (LOGIN, EMAIL, PHONE, PASSWORD, ROLE_ID,FIRST_NAME,SECOND_NAME)
			VALUES (:login,:email,:phone,:password,2,:firstName,:secondName)'
		);

		$preparedQuery->execute(
			[
				'login' => $user->getLogin(),
				'email' => $user->getEmail(),
				'phone' => $user->getPhone(),
				'password' => $password,
				'firstName' => $user->getFirstName(),
				'secondName' => $user->getSecondName(),
			]
		);
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
            uu.ID as USER_ID,
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
		$values=[];
		if ($login !== '')
		{
			$query = $query . " WHERE uu.LOGIN=:login";
			$values['login']=$login;
		}
		$query = $this->DBConnection->prepare($query);
		$query->execute($values);
		$resultList = [];
		while ($row = $query->fetch())
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
			$row['USER_ID'],
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
		$query = $this->DBConnection->prepare("UPDATE up_user SET ROLE_ID=:roleId WHERE LOGIN=:login");
		$query->execute(['login'=>$login,'roleId'=>$roleId]);
	}

	public function updateUser(User $user): void
	{
		$query = "
			UPDATE up_user SET FIRST_NAME = ? , SECOND_NAME = ? , PHONE = ? , EMAIL = ?
			WHERE ID = {$user->getId()}";
		$preparedStatement = $this->DBConnection->prepare($query);
		$preparedStatement->execute(
			[$user->getFirstName(), $user->getSecondName(), $user->getPhone(), $user->getEmail()]);
	}

}
