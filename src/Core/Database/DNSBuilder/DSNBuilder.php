<?php

namespace Up\Core\DataBase\DNSBuilder;

use Up\Core\DatabaseConfig;

interface DSNBuilder
{
	public static function createDSN(DatabaseConfig $databaseConfig, array $options = []): string;
}