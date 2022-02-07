<?php

namespace Up\Core\Database;

use Up\Core\Database\DSNBuilder\MysqlDSNBuilder;
use Up\Core\Settings;

class DefaultDatabase extends BaseDatabase
{
	protected static $dnsOptions = [
		'charset' => 'UTF8',
	];

	protected function __construct()
	{
		self::$databaseConfig = Settings::getInstance()->getDataBaseConfig();
		self::$dnsBuilder = new MysqlDSNBuilder();
		parent::__construct();
	}

	public static function getInstance(): self
	{
		// сделал чтобы работало автодополнение в ide
		return parent::getInstance();
	}
}