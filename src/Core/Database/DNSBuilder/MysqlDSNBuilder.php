<?php

namespace Up\Core\DataBase\DNSBuilder;

class MysqlDSNBuilder extends ConnectableDatabaseDSNBuilder
{
	protected static $dnsDatabasePrefix = 'mysql:';
}