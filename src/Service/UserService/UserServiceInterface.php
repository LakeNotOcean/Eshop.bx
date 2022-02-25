<?php

namespace Up\Service\UserService;

use Up\Entity\User\User;
use Up\Entity\User\UserEnum;

interface UserServiceInterface
{
	public function authorizeUserByLogin(string $login, string $password): void;

	public function giveUserModeratorRights(string $login);

	public function giveUserAdministratorRoleByLogin(string $login);

	public function removeUserModeratorRights(string $login);

	public function changeUserRoleByLogin(string $login, int $roleId);

	public function getUserInfo(): User;

	public function getUsersInfo(): array;

	public function getUserInfoById(int $id): User;

	public function hasPermission(UserEnum $role): bool;

	public function getUserListByRole(int $roleId): array;

	public function getAmountUserByQuery(int $roleId,string $query): int;

	public function getUserListByQuery(array $limitOffset, int $roleId,string $query): array;

	public function getAllRoles(): array;

	public function registerUser(User $user, string $password): User;

	public function removeUserFromSession(): void;

	public function isAuthenticated(): bool;

	public function updateUser(User $user): void;

	public function checkIsAdmin(): void;

	public function isValidPassword(string $password, User $user): bool;

	public function updatePassword(string $newPassword, User $user): void;
}
