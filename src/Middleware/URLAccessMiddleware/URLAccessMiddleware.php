<?php

namespace Up\Middleware\URLAccessMiddleware;

use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\Middleware\AbstractMiddleware;


class URLAccessMiddleware extends AbstractMiddleware
{
	/**
	 * @var array<string, string>
	 *     Сопоставляются названия путей и из права доступа,
	 */
	private $accessConfig = [
		'add-item' => AccessLevel::Moderator,
	];

	public function __invoke(Request $request, ...$params): Response
	{
		// TODO: Написать в нем логику
		return call_user_func($this->getResponse, $request, ...$params);
	}
}
