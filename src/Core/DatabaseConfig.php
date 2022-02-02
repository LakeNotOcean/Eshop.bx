<?php

namespace Up\Core;


/** Содержит всю необходимую информацию для подключения к БД */

class DatabaseConfig
{
	private $host;
	private $user;
	private $password;
	private $dbName;

	public function __construct(string $host,string $user, string $password, string $dbName)
	{
		$this->host=$host;
		$this->user=$user;
		$this->password=$password;
		$this->dbName=$dbName;

	}
	public function getHost():string
	{
		return $this->host;
	}
	public function getDbName():string
	{
		return $this->dbName;
	}

	public function getUser():string
	{
		return $this->user;
	}

	public function getPassword():string
	{
		return $this->password;
	}

}