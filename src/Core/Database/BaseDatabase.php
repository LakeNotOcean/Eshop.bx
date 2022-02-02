<?php

namespace Up\Core\DataBase;

abstract class BaseDatabase
{
	protected static $settings;
	protected static $instance;
	protected $databaseAdapter;

	protected function __construct()
	{
	}

	public static function getInstance()
	{
		if (is_null(static::$instance))
		{
			static::$instance = new static();
		}

		return static::$instance;
	}

	public function __clone()
	{
	}

	public function __wakeup()
	{
	}
}