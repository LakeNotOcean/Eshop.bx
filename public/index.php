<?php

require '../Autoloader.php';

$autoloader = Autoloader::getInstance();
$autoloader->addVendorNamespacePath('Up\\',$_SERVER['DOCUMENT_ROOT'] . '/src/');

