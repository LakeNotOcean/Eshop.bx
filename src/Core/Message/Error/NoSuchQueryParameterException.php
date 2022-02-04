<?php

namespace Up\Core\Message\Error;

use Exception;
use Throwable;

class NoSuchQueryParameterException extends Exception
{
	public function __construct($message, $code = 0, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

	public function __toString()
	{
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}
}