<?php

namespace Up\Core\Settings;


/** Синглтон, используется для получения информации конфигурации */
class Settings
{
	private static $instance;
	private $settingsList;

	protected function __construct()
	{
		$this->settingsList = parse_ini_file(__DIR__ . '/../config.ini', false, INI_SCANNER_TYPED);
		$this->settingsList['databaseConfig'] = new DatabaseConfig(
			$this->settingsList['host'],
			$this->settingsList['user'],
			$this->settingsList['password'],
			$this->settingsList['dbName'],
			(int)$this->settingsList['port']
		);
		$removeFields = ['host', 'user', 'password', 'dbName', 'port'];
		$this->settingsList = array_diff_key($this->settingsList, array_flip($removeFields));
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

	public function getSettings(string $paramName)
	{
		return $this->settingsList[$paramName];
	}
}
