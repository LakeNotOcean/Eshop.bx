<?php

namespace Up\DAO\UserDAO;

use Up\Entity\User\User;


interface UserDAOInterface
{

	public function authenticateUser(string $login, string $password): bool;

	public function getUserByLogin(string $login): User;

	public function addUser(User $user, string $password): User;

	public function giveUserModeratorRoleByLogin(string $login): void;

	public function removeUserModeratorRoleByLogin(string $login): void;

	public function changeUserRoleByLogin(string $login,int $roleId): void;

	public function giveUserAdministratorRoleByLogin(string $login): void;

	public function getUsersInfo(): array;

	public function checkUniqueFields(User $user): array;

	public function getAllRoles(): array;

	public function getUserInfoById(int $userId): User;

	public function getUserListByRole(int $roleId): array;

	public function getAmountUserByQuery(int $roleId,string $querySearch): int;

	public function getUserListByQuery(int $offset, int $amountItems,int $roleId,string $querySearch):array;

	public function updateUser(User $user): void;

	public function updatePassword(User $user, string $newPassword): void;

}
