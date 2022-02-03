<?php

namespace Up\Core\DataBase;

use mysqli;
use Up\Core\Settings;

class DefaultDatabase extends BaseDatabase
{
	protected function __construct()
	{
		parent::__construct();

		$settings = Settings::getInstance()->getDataBaseConfig();
		static::$settings = $settings;

		$this->databaseAdapter = new mysqli();
		$connectionResult = $this->databaseAdapter->real_connect(
			$settings->getHost(),
			$settings->getUser(),
			$settings->getPassword(),
			$settings->getDbName()
		);
		if (!$connectionResult)
		{
			$error = $this->databaseAdapter->error . $this->databaseAdapter->errno;
			trigger_error($error, E_USER_ERROR);
		}
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

	/** Не возвращает данные */
	public function multiQuery(string $query):bool
	{
		$result=$this->databaseAdapter->multi_query($query);
		if ($result)
		{
			while($this->databaseAdapter->more_results())
			{
				$this->databaseAdapter->next_result();
				if($res = $this->databaseAdapter->store_result())
				{
					$res->free();
				}
			}
		}
		return $result;
	}

	public function prepare(string $query)
	{
		return $this->databaseAdapter->prepare($query);
	}

	public function getErrorMessage():string
	{
		return $this->databaseAdapter->error;
	}
}