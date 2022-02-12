<?php

declare(strict_types=1);

$start = microtime(true); // Top of page

require '../Autoloader.php';


$autoloader = Autoloader::getInstance();

$autoloader->addVendorNamespacePath('Up\\', __DIR__ . '/../src/');

session_start();

Up\Core\Application::run();

$end = microtime(true); // Bottom of page

$time = $end - $start;
$content ="Page loaded in $time seconds";
?>

<div style="position: absolute; z-index: 999; top: 0; left: 0">
	<?= $content?>
</div>
