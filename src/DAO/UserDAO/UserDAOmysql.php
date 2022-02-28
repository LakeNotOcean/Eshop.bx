<?php

namespace Up\DAO\UserDAO;

use Up\Core\Database\DefaultDatabase;
use Up\Core\Password;
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

		return array_key_exists($login, $resultList) && Password::verifyPassword($password, $resultList[$login]);
	}

	/**
	 * @throws \ReflectionException
	 * @throws \Up\Core\Enum\EnumException
	 */
	public function getUserByLogin(string $login): User
	{
		$userList = $this->getUsersList($login);

		return $userList[$login];
	}

	/**
	 * @param User $user
	 * @param string $password
	 *
	 * @return User
	 */
	public function addUser(User $user, string $password): User
	{
		$password = PassWord::hashPassword($password);
		try
		{
			$this->DBConnection->beginTransaction();
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
			$newUser = clone $user;
			$newUser->setId($this->DBConnection->lastInsertId());
			$this->DBConnection->commit();

			return $newUser;
		}
		catch (\PDOException $pdoException)
		{
			$this->DBConnection->rollBack();
			throw $pdoException;
		}
	}

	public function giveUserModeratorRoleByLogin(string $login): void
	{
		$this->changeRole($login, 3);
	}

	public function removeUserModeratorRoleByLogin(string $login): void
	{
		$this->changeRole($login, 2);
	}

	public function changeUserRoleByLogin(string $login,int $roleId):void
	{
		$this->changeRole($login,$roleId);
	}

	public function giveUserAdministratorRoleByLogin(string $login): void
	{
		$this->changeRole($login, 1);
	}

	/**
	 * @throws \ReflectionException
	 * @throws \Up\Core\Enum\EnumException
	 */
	public function getUsersInfo(): array
	{
		return $this->getUsersList();
	}

	public function checkUniqueFields(User $user): array
	{
		$statement = $this->getCheckUniqueFieldsStatement();
		$statement->execute([$user->getLogin(), $user->getEmail(), $user->getPhone()]);
		$row = $statement->fetch();
		$result['login'] = !((int)$row['login'] === 0);
		$result['email'] = !((int)$row['email'] === 0);
		$result['phone'] = !((int)$row['phone'] === 0);
		return $result;
	}

	public function getCheckUniqueFieldsStatement(): \PDOStatement
	{
		$query = "SELECT SUM(uu.LOGIN = ?) login,
                         SUM(uu.EMAIL = ?) email,
                         SUM(uu.PHONE = ?) phone
				  FROM up_user uu;";
		return $this->DBConnection->prepare($query);
	}

	public function getAllRoles(): array
	{
		$query = "SELECT NAME FROM up_role";
		$result = $this->DBConnection->query($query);
		$roles = [];
		while ($row = $result->fetch())
		{
			$roles[] = new UserRole(new UserEnum($row["NAME"]));
		}
		return $roles;
	}


	/**
	 * @throws \Up\Core\Enum\EnumException
	 * @throws \ReflectionException
	 */
	public function getUserInfoById(int $userId): User
	{
		$query = $this->getUserInfoByIdQuery($userId);
		$query = $this->DBConnection->query($query);
		$row = $query->fetch();
		return $this->createUserByRow($row);
	}

	/**
	 * @throws \ReflectionException
	 * @throws \Up\Core\Enum\EnumException
	 */
	public function getUserListByRole(int $roleId): array
	{
		$query = $this->getUserListByRoleQuery($roleId);
		$query = $this->DBConnection->query($query);
		$resultList = [];
		while ($row = $query->fetch())
		{
			$resultList[$row['USER_LOGIN']] = $this->createUserByRow($row);
		}
		return $resultList;
	}


	public function getAmountUserByQuery(int $roleId,string $querySearch): int
	{
		$query = $this->getAmountUsersByQueryQuery($roleId);
		$preparedQuery = $this->DBConnection->prepare($query);
		$preparedQuery->execute(["%{$querySearch}%","%{$querySearch}%","%{$querySearch}%","%{$querySearch}%"]);
		$row = $preparedQuery->fetch();
		return $row["USERS_AMOUNT"];
	}

	/**
	 * @throws \ReflectionException
	 * @throws \Up\Core\Enum\EnumException
	 */
	public function getUserListByQuery(int $offset, int $amountItems, int $roleId, string $querySearch):array
	{
		$query = $this->getUserListByQueryQuery($roleId,$offset, $amountItems);
		$preparedQuery = $this->DBConnection->prepare($query);
		$preparedQuery->execute(["%{$querySearch}%","%{$querySearch}%","%{$querySearch}%","%{$querySearch}%"]);
		$resultList = [];
		while ($row = $preparedQuery->fetch())
		{
			$resultList[$row['USER_LOGIN']] = $this->createUserByRow($row);
		}
		return $resultList;
	}

	/**
	 * @throws \ReflectionException
	 * @throws \Up\Core\Enum\EnumException
	 */
	private function getUsersList(string $login = ''): array
	{
		$query = $this->getUserListQuery($login);
		$values=[];
		if ($login !== '')
		{
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

	public function updateUser(User $user): void
	{
		$query = "
			UPDATE up_user SET FIRST_NAME = ? , SECOND_NAME = ? , PHONE = ? , EMAIL = ?
			WHERE ID = {$user->getId()}";
		$preparedStatement = $this->DBConnection->prepare($query);
		$preparedStatement->execute(
			[$user->getFirstName(), $user->getSecondName(), $user->getPhone(), $user->getEmail()]);
	}

	public function updatePassword(User $user, string $newPassword): void
	{
		$newPasswordHash = Password::hashPassword($newPassword);

		$query = "UPDATE up_user SET PASSWORD = '{$newPasswordHash}' WHERE ID = {$user->getId()}";
		$this->DBConnection->query($query);
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

	private function getUserListQuery(string $login): string
	{
		$query = $this->getCommonUserQuery();
		if ($login !== '')
		{
			$query .=" WHERE uu.LOGIN=:login";
		}
		return $query;
	}

	private function getUserListByQueryQuery(int $roleId, int $offset, int $amountItems):string
	{
		$query = "WHERE (LOGIN LIKE ? OR EMAIL LIKE ? OR FIRST_NAME LIKE ? OR SECOND_NAME LIKE ?)";
		$query = $this->getCommonUserQuery($query);
		if ($roleId !== 0)
		{
			$query .= " AND ROLE_ID = {$roleId} ";
		}
		$query .= "ORDER BY ROLE_ID DESC
		 LIMIT {$offset}, {$amountItems} ";
		return $query;
	}

	private function getAmountUsersByQueryQuery(int $roleId): string
	{
		$query = "SELECT
		COUNT(*) as USERS_AMOUNT
		FROM up_user
		LEFT JOIN up_role ur on ur.ID = ROLE_ID
		WHERE (LOGIN LIKE ? OR EMAIL LIKE ? OR FIRST_NAME LIKE ? OR SECOND_NAME LIKE ?)";
		if ($roleId !== 0)
		{
			$query .= " AND ROLE_ID = {$roleId} ";
		}
		return $query;
	}

	private function getUserListByRoleQuery(int $roleId): string
	{
		$query = "WHERE ROLE_ID = {$roleId}";
		$query = $this->getCommonUserQuery($query);
		return $query;
	}

	private function getUserInfoByIdQuery(int $userId): string
	{
		$query = "WHERE uu.ID = {$userId}";
		$query = $this->getCommonUserQuery($query);
		return $query;
	}

	private function getCommonUserQuery(string $query = ''): string
	{
		$newQuery = "SELECT
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
		LEFT JOIN up_role ur on ur.ID = uu.ROLE_ID ";
		$newQuery .= $query;
		return $newQuery;
	}

}
