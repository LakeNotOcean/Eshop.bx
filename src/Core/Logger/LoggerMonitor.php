<?php

namespace Up\Core\Logger;

 class LoggerMonitor
{
	static function warn()
	{
		$message = "Line 1\r\nLine 2\r\nLine 3";
		$message = wordwrap($message, 70, "\r\n");
		$result = mail('oleg.kalyugin2001@mail.ru', 'My Subject', $message);
		var_dump($result);
	}
}