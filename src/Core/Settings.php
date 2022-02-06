<?php

namespace Up\Core;

/** Синглтон, используется для получения информации конфигурации */
class Settings
{
	private static $instance;
	private $databaseConfig;
	private $isDev;
	private $sessionLifetime;
	private $migrationDirPath;
	private $DIConfigPath;

	protected function __construct()
	{
		$config = parse_ini_file('config.ini');
		$this->databaseConfig = new DataBaseConfig(
			$config['host'], $config['user'], $config['password'], $config['dbName'], (int)$config['port']
		);
		$this->isDev = $config['isDev'];
		$this->sessionLifetime = $config['sessionLifetime'];
		$this->migrationDirPath = $config['migrationDirPath'];
		$this->DIConfigPath = $config['DIConfigPath'];
	}

	public static function getInstance(): Settings
	{
		if (isset(self::$instance))
		{
			return self::$instance;
		}

		self::$instance = new self();

		return self::$instance;
	}

	public function getDataBaseConfig(): DataBaseConfig
	{
		return $this->databaseConfig;
	}

	public function isDev(): bool
	{
		return $this->isDev;
	}

	public function getSessionLifetime(): bool
	{
		return $this->sessionLifetime;
	}

	public function getMigrationDirPath(): string
	{
		return $this->migrationDirPath;
	}

	public function getDIConfigPath(): string
	{
		return $this->DIConfigPath;
	}
}