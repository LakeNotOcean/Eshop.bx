<?php

require '../Autoloader.php';

$autoloader = Autoloader::getInstance();
$autoloader->addVendorNamespacePath('Up\\',$_SERVER['DOCUMENT_ROOT'] . '/src/');

$migration=new Up\Core\Migration\MigrationManager(Up\Core\DataBaseConnect::getInstance()->getDataBase());

$migration->addAndApplyMigration('CREATE TABLE  test (test varchar(20))',"test");

echo 'it is work';