<?php

namespace Up\Entity\User;

use Up\Core\Enum\Enum;

class UserEnum extends Enum
{
	public const Guest = "Guest";
	public const User = "User";
	public const Moderator = "Moderator";
	public const Admin = "Admin";
}
