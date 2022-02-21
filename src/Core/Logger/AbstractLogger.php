<?php

namespace Up\Core\Logger;

use Exception;
use Up\Core\Error\DirectoryNotExist;
use Up\Core\Logger\Error\InvalidArgumentException;

/**
 * В месте, где необходимо залогировать происшествие требуется создать объект логгер,
 * конструктор принимает необязательный параметр, который отвечает за путь к месту логирование.
 * Существует 8 уровней событий логгирования по PSR-3, они описанны в LoggerInterface.
 * Для записи в лог файл нужно вызвать метод log обьекта logger.
 * log принимает 3 параметра.Первый - loglevel, уровень события ('emergency','alert','critical',
 * 'error','warning','notice','info','debug'). Второй - сообщение, если логируется исключение,
 * то необходимо передать само исключение, а не его сообщение(метод getMessage класса Exception),
 * если передавать строку, тогда можно в строке указать место для параметра ("Пользоватьель {userName} посетил {url}")
 * Третий - контекст, это массив, который включает в себе ключ параметра в сообщении и его значение,
 * для примера выше (['userName' => $userName], ['url' => 'eshop' . $url]).
 */
abstract class AbstractLogger implements LoggerInterface
{
	protected const LOGGING_PATH = SOURCE_DIR . 'Log/';
	protected static $instance;
	protected $baseLogDir;
	protected $name;

	/**
	 * @param string $name
	 * @param string $baseLogDir
	 *
	 * @throws DirectoryNotExist
	 */
	protected function __construct(string $name, string $baseLogDir)
	{
		if (!is_dir($baseLogDir))
		{
			throw new DirectoryNotExist("Directory '{$baseLogDir}' does not exist");
		}

		$this->baseLogDir = $baseLogDir;
		$this->name = $name;
	}

	/**
	 * @throws DirectoryNotExist
	 */
	public static function getLogger(string $name, string $baseLogDir = self::LOGGING_PATH)
	{
		if (is_null(static::$instance))
		{
			static::$instance = new static($name, $baseLogDir);
		}

		return static::$instance;
	}

	private function interpolate($message, array $context = []): string
	{
		$replace = [];
		foreach ($context as $key => $val)
		{
			if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString')))
			{
				$replace['{' . $key . '}'] = $val;
			}
		}

		return strtr($message, $replace);
	}

	private function trace(): string
	{
		$record = '';
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		foreach ($backtrace as $key => $el)
		{
			if ($key > 4)
			{
				$record .= '#'
					. $key
					. ' '
					. $el['class']
					. $el['type']
					. $el['function']
					. '() called at ['
					. $el['file']
					. ':'
					. $el['line']
					. ']'
					. PHP_EOL;
			}
		}

		return $record;
	}

	protected function createMessage($message, array $context, bool $isTrace = false): string
	{
		$body = '';
		if (($message instanceof Exception) && $isTrace)
		{
			$body = $message;
		}
		elseif ($message instanceof Exception && !$isTrace)
		{
			$body = $message->getMessage();
		}
		elseif (method_exists($message, '__toString'))
		{
			$body = $message;
		}
		elseif (is_string($message) && $isTrace)
		{
			$body = $this->interpolate($message, $context);
			$trace = $this->trace();
			$body .= ' ' . $trace;
		}
		elseif (is_string($message) && !$isTrace)
		{
			$body = $this->interpolate($message, $context);
		}

		return $body;
	}
}
