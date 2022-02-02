<?php

namespace Up\Core;

use mysqli;


/** тестовый класс для подключения к БД */
class DataBaseConnect
{
	private static $instance;
	private $database;

	protected function __construct()
	{
		$databaseConfig=Settings::getInstance()->getDataBaseConfig();
		$database=mysqli_init();
		$connectionResult = mysqli_real_connect(
			$database,
			$databaseConfig->getHost(),
			$databaseConfig->getUser(),
			$databaseConfig->getPassword(),
			$databaseConfig->getDbName()
		);
		if (!$connectionResult)
		{
			$error = mysqli_connect_errno() . ": ". mysqli_connect_error();
			trigger_error($error, E_USER_ERROR);
		}
		$charsetResult=mysqli_set_charset($database,'utf8');
		if (!$charsetResult)
		{
			trigger_error($database->error,E_USER_ERROR);
		}
		$this->database=$database;
	}

	public static function getInstance():DataBaseConnect
	{
		if (isset(self::$instance))
		{
			return self::$instance;
		}

		self::$instance = new self();

		return self::$instance;
	}

	public function getDataBase():mysqli
	{
		return $this->database;
	}

}