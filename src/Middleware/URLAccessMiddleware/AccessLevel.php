<?php

namespace Up\Middleware\URLAccessMiddleware;

class AccessLevel
{
	public const All = 1;
	public const Registered = 2;
	public const Moderator = 3;
	public const Admin = 4;
}