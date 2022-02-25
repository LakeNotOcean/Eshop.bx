<?php

namespace Up\Core\Logger\Application;

use DateTime;
use DateTimeInterface;
use DirectoryIterator;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Up\Core\Error\OSError;
use Up\Core\Logger\AbstractLogger;
use Up\Core\Logger\LogLevel;
use Up\Core\Settings\Settings;
use Up\Lib\File\JsonFile;
use Up\Lib\Observer\ObserverInterface;
use ZipArchive;

/**
 * @initMethod getLogger
 */
class ApplicationLogger extends AbstractLogger
{
	protected const FILENAME_FORMAT = 'Y-m-d';
	protected const DATE_FORMAT = DateTimeInterface::ATOM;
	protected const COMPRESS_TIME_DELTA = '2 days';
	protected $appendDataToFileFunc = [JsonFile::class, 'push'];
	protected $logFileExtension = 'json';
	protected $logFilePath;
	protected $backupDir;
	protected $allowedLogLevels = [];
	/**
	 * @var array Если в settings указан logLevel с индексом n, то логироваться будут все сообщения
	 *    того же logLevel или с индексом большим n
	 */
	protected $logLeverOrder = [
		LogLevel::Debug,
		LogLevel::Info,
		LogLevel::Notice,
		LogLevel::Warning,
		LogLevel::Error,
		LogLevel::Critical,
		LogLevel::Emergency,
		LogLevel::Wtf,
	];
	private $observers = [
		LogLevel::Emergency => [],
		LogLevel::Wtf => [],
		LogLevel::Critical => [],
		LogLevel::Error => [],
		LogLevel::Warning => [],
		LogLevel::Notice => [],
		LogLevel::Info => [],
		LogLevel::Debug => [],
	];

	protected function __construct(string $name, string $baseLogDir)
	{
		parent::__construct($name, $baseLogDir);
		$this->backupDir = $baseLogDir . 'backups/';
		$settingsLogLevel = Settings::getInstance()->getSettings('logLevel');
		$this->allowedLogLevels = $this->getAllowedLogLevels(new LogLevel($settingsLogLevel));
		$this->logFilePath = $this->createLogFile();
		$this->createLogBackupDir();
		$this->compressOldLogs();
	}

	private function getAllowedLogLevels(LogLevel $logLevel): array
	{
		$logLevelStartIndex = array_search($logLevel->getValue(), $this->logLeverOrder, true);
		$result = [];

		for (
			$logLeveLIndex = $logLevelStartIndex, $logLeveLIndexMax = count($this->logLeverOrder) - 1;
			$logLeveLIndex <= $logLeveLIndexMax; $logLeveLIndex++
		)
		{
			$result[] = $this->logLeverOrder[$logLeveLIndex];
		}

		return $result;
	}

	private function createLogFile(): string
	{
		$filePath = $this::LOGGING_PATH . date($this::FILENAME_FORMAT) . '.' . $this->logFileExtension;
		if (!file_exists($filePath))
		{
			fclose(fopen($filePath, 'wb'));
		}

		return $filePath;
	}

	private function createLogBackupDir(): void
	{
		if (is_dir($concurrentDirectory = $this->backupDir))
		{
			return;
		}
		if (!mkdir($concurrentDirectory) && !is_dir($concurrentDirectory))
		{
			throw new OSError(sprintf('Directory "%s" was not created', $concurrentDirectory));
		}
	}

	protected function compressOldLogs(): void
	{
		foreach (new DirectoryIterator($this->baseLogDir) as $file)
		{
			if ($file->isDir() || $file->isDot())
			{
				continue;
			}
			try
			{
				$fileCreationDate = new DateTime(pathinfo($file->getFilename(), PATHINFO_FILENAME));
			}
			catch (\Exception $e)
			{
				continue;
			}
			$compressDate = $fileCreationDate->modify($this::COMPRESS_TIME_DELTA);
			if (
				$compressDate <= new DateTime()
			)
			{
				$compressedFilename = $file->getFilename() . '.zip';
				$compressedFilePath = $this->backupDir . $compressedFilename;
				$this->compessLog($file->getRealPath(), $compressedFilePath);
				unlink($file->getRealPath());
			}
		}
	}

	protected function compessLog(string $filePath, string $sendTo): void
	{
		$zip = new ZipArchive();
		$zip->open($sendTo, ZipArchive::CREATE);
		$zip->addFile($filePath, pathinfo($filePath, PATHINFO_BASENAME));
		$zip->close();
	}

	public static function getLogger(string $name = __CLASS__, string $baseLogDir = self::LOGGING_PATH): self
	{
		return parent::getLogger($name, $baseLogDir);
	}

	public function emergency($message, array $context = []): void
	{
		$this->log(LogLevel::Emergency(), $message, $context);
	}

	public function log(LogLevel $loglevel, $message, array $context = []): void
	{
		if (!in_array($loglevel->getValue(), $this->allowedLogLevels, true))
		{
			return;
		}

		$data = $this->prepareDataToSave($loglevel, $message, $context);
		$this->pushDataToLogFile($data, $this->logFilePath);
		$this->notifyObservers($loglevel, $message);
	}

	private function prepareDataToSave(LogLevel $logLevel, $message, array $context = []): array
	{
		$result = [
			'loggerName' => $this->name,
			'logLevel' => $logLevel->getValue(),
			'dateTime' => date($this::DATE_FORMAT),
			'message' => $this->createMessage($message, []),
		];
		if (!empty($context))
		{
			$result['context'] = $context;
		}

		return $result;
	}

	private function pushDataToLogFile($data, string $path)
	{
		($this->appendDataToFileFunc)($data, $path);
	}

	private function notifyObservers(LogLevel $logLevel, $message)
	{
		$allowedLogLevelNames = $this->getAllowedLogLevels($logLevel);
		$observers = [];
		foreach ($allowedLogLevelNames as $allowedLogLevelName)
		{
			$observers[] = $this->observers[$allowedLogLevelName];
		}

		/** @var ObserverInterface $observer */
		foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($observers)) as $observer)
		{
			$observer->update($logLevel, $message);
		}
	}

	public function alert($message, array $context = []): void
	{
		$this->log(LogLevel::Alert(), $message, $context);
	}

	public function critical($message, array $context = []): void
	{
		$this->log(LogLevel::Critical(), $message, $context);
	}

	public function error($message, array $context = []): void
	{
		$this->log(LogLevel::Error(), $message, $context);
	}

	public function warning($message, array $context = []): void
	{
		$this->log(LogLevel::Warning(), $message, $context);
	}

	public function notice($message, array $context = []): void
	{
		$this->log(LogLevel::Notice(), $message, $context);
	}

	public function info($message, array $context = []): void
	{
		$this->log(LogLevel::Info(), $message, $context);
	}

	public function debug($message, array $context = []): void
	{
		$this->log(LogLevel::Debug(), $message, $context);
	}

	public function subscribe(LogLevel $level, ObserverInterface $observer): void
	{
		$this->observers[$level->getValue()][] = $observer;
	}
}