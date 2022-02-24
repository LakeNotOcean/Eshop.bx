<?php

namespace Up\Service\UserService;

use Up\Entity\User\User;

interface UserServiceInterface
{
	public function authorizeUserByLogin(string $login, string $password): void;

	public function giveUserModeratorRights(string $login);

	public function giveUserAdministratorRoleByLogin(string $login);

	public function removeUserModeratorRights(string $login);

	public function getUserInfo(): User;

	public function getUsersInfo(): array;

	public function getUserInfoById(int $id): User;

	public function getUserListByRole(int $roleId): array;

	public function getAmountUserByQuery(int $roleId,string $query): int;

	public function getUserListByQuery(array $limitOffset, int $roleId,string $query): array;

	public function registerUser(User $user, string $password): User;

	public function removeUserFromSession(): void;

	public function updateUser(User $user): void;

	public function isValidPassword(string $password, User $user): bool;
}
