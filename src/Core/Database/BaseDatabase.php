<?php

namespace Up\Core\Database;

use PDO;
use Up\Core\Database\DSNBuilder\DSNBuilder;
use Up\Core\Settings\DatabaseConfig;

abstract class BaseDatabase extends PDO
{
	protected static $instance;
	/**
	 * @var DatabaseConfig
	 */
	protected static $databaseConfig;
	/**
	 * @var DSNBuilder
	 */
	protected static $dnsBuilder;
	protected static $dnsOptions = [];
	private const errorMode = self::ERRMODE_EXCEPTION;

	protected function __construct()
	{
		$dsn = static::$dnsBuilder::createDSN(static::$databaseConfig, self::$dnsOptions);
		parent::__construct(
			$dsn,
			static::$databaseConfig->getUser(),
			static::$databaseConfig->getPassword(),
			self::$dnsOptions
		);
		$this->setAttribute(self::ATTR_ERRMODE, self::errorMode);
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
}