<?php

namespace Up\Core\Error;

use Throwable;

class ExceptionWithMessagesForUser extends \Exception
{
	/**
	 * @var array<string>
	 */
	protected $messagesForUser = [];
	protected $status = 200;

	public function __construct($message = "", $messagesForUser = [], $status = 400, $code = 0, Throwable $previous = null)
	{
		$this->messagesForUser = $messagesForUser;
		$this->status = $status;
		parent::__construct($message, $code, $previous);
	}

	/**
	 * @return array<string>
	 */
	public function getMessages(): array
	{
		return $this->messagesForUser;
	}

	public function getStatus(): int
	{
		return $this->status;
	}
}