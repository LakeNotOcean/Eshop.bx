<?php

namespace Up\Core\Logger;

use Up\Core\Enum\Enum;

class LogLevel extends Enum
{
	public const Debug = 'debug';
	public const Info = 'info';
	public const Notice = 'notice';
	public const Warning = 'warning';
	public const Error = 'error';
	public const Critical = 'critical';
	public const Wtf = 'wtf';
	public const Emergency = 'emergency';
}