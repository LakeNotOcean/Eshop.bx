<?php

namespace Up\Service\UserService;

use Up\Entity\User\User;

interface UserServiceInterface
{
	public function authorizeUserByLogin(string $login, string $password): void;

	public function giveUserModeratorRights(string $login);

	public function removeUserModeratorRights(string $login);

	public function getUserInfo(): User;

	public function getUsersInfo(): array;

	public function registerUser(User $user, string $password): void;

	public function removeUserFromSession(): void;

	public function updateUser(User $user): void;

}
