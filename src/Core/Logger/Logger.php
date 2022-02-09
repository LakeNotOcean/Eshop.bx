<?php

namespace Up\Core\Logger;

use Exception;
use Up\Core\Logger\Error\DirectoryNotExist;
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



class Logger implements LoggerInterface
{
	protected const ORIGINAL_PATH = '../src/Log/';
	protected $PATH;
	protected $file;

	protected static $level = [
		'emergency' ,
		'alert'     ,
		'critical'  ,
		'error'     ,
		'warning'   ,
		'notice'    ,
		'info'      ,
		'debug'     ,
	];

	/**
	 * @param string $PATH
	 */
	public function __construct(string $PATH = self::ORIGINAL_PATH)
	{
		if (is_dir($PATH))
		{
			$this->PATH = $PATH;
		}
		else
		{
			$this->PATH = self::ORIGINAL_PATH;
		}
	}

	public function log(string $loglevel, $message, array $context = []): void
	{

		if (!in_array($loglevel,self::$level))
		{
			throw new InvalidArgumentException();
		}
		else
		{
			$this->open($loglevel);
		}
		call_user_func(array($this,$loglevel),$message,$context);
	}

	private function open($fileName): void
	{
		if (!is_dir($this->PATH))
		{
			mkdir($this->PATH);
		}
		$filePATH = $this->PATH . $fileName . '.txt';
		$this->file = fopen($filePATH, 'a+') ;
	}
	private function interpolate($message, array $context = array()): string
	{
		$replace = array();
		foreach ($context as $key => $val) {
			if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
				$replace['{' . $key . '}'] = $val;
			}
		}
		return strtr($message, $replace);
	}

	private function trace(): string
	{
		$record = '';
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		foreach ($backtrace as $key => $el) {
			if ($key > 4) {
				$record .= '#' . $key . ' ' . $el['class'] . $el['type'] . $el['function'] . '() called at [' . $el['file'] . ':' . $el['line'] . ']' . PHP_EOL;
			}
		}
		return $record;
	}

	private function createMessage($message,array $context,bool $isTrace = false): string
	{
		$dataTime = '['.date('D M d H:i:s Y',time()).'] ';
		$body = '';
		if (($message instanceof Exception) && $isTrace )
		{
			$body = $message;
		}
		elseif ($message instanceof Exception && $isTrace === false)
		{
			$body = $message->getMessage();
		}
		elseif (method_exists($message, '__toString'))
		{
			$body = $message;
		}
		elseif (is_string($message) && $isTrace)
		{
			$body = $this->interpolate($message,$context);
			$trace = $this->trace();
			$body = $body . ' ' . $trace;
		}
		elseif (is_string($message) && $isTrace === false)
		{
			$body = $this->interpolate($message,$context);
		}
		$message = $dataTime . ' ' . $body . PHP_EOL;
		return $message;
	}



	public function emergency($message, array $context = array())
	{

		$finalMessage = $this->createMessage($message,$context,1);
		fwrite($this->file,$finalMessage);
		LoggerMonitor::warn();
	}


	public function alert($message, array $context = array())
	{

	}


	public function critical($message, array $context = array())
	{

	}


	public function error($message, array $context = array())
	{

	}


	public function warning($message, array $context = array())
	{
		$finalMessage = $this->createMessage($message,$context,1);
		fwrite($this->file,$finalMessage);
	}


	public function notice($message, array $context = array())
	{
		$finalMessage = $this->createMessage($message,$context,0);
		fwrite($this->file,$finalMessage);
	}


	public function info($message, array $context = array())
	{
		$finalMessage = $this->createMessage($message,$context,1);
		fwrite($this->file,$finalMessage);
	}


	public function debug($message, array $context = array())
	{

	}


}