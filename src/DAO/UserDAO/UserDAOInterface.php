<?php

namespace Up\DAO\UserDAO;

use Up\Entity\User\User;

interface UserDAOInterface
{
	public function authenticateUser(string $login, string $password): bool;

	public function getUserByLogin(string $login): User;

	public function addUser(User $user, string $password): void;

	public function giveUserModeratorRoleByLogin(string $login): void;

	public function removeUserModeratorRoleByLogin(string $login): void;

	public function getUsersInfo(): array;

}
