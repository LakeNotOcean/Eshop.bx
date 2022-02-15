<?php

namespace Up\Entity\User;

use Up\Core\Enum\Enum;

class UserEnum extends Enum
{
	const Guest="Guest";
	const User="User";
	const Moderator="Moderator";
	const Admin="Admin";
}