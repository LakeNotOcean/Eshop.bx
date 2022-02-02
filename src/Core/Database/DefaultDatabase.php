<?php

namespace Up\Core\DataBase;

use mysqli;

class DefaultDatabase extends BaseDatabase
{
	protected function __construct()
	{
		parent::__construct();

		$settings = Settings::getInstanse()->DatabaseSettings;
		static::$settings = $settings;

		$this->databaseAdapter = new mysqli(
			$settings->getHost(),
			$settings->getUser(),
			$settings->getPassword(),
			$settings->getDbName()
		);
		$this->databaseAdapter->set_charset('utf8');
	}

	public static function getInstance(): self
	{
		// сделал чтобы работало автодополнение в ide
		return parent::getInstance();
	}

	public function query(string $query, int $result_mode = MYSQLI_STORE_RESULT)
	{
		return $this->databaseAdapter->query($query, $result_mode);
	}

	public function prepare(string $query)
	{
		return $this->databaseAdapter->prepare($query);
	}
}