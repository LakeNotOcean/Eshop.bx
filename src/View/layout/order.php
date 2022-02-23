<?php
/** @var string $content */

/** @var int $orderSize */
/** @var int $cost */

$darkMode = true;

?>

<!doctype html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>EShop</title>
	<link rel="apple-touch-icon" sizes="120x120" href="/img/logo/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/img/logo/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/img/logo/favicon-16x16.png">
	<link rel="manifest" href="/img/logo/site.webmanifest">
	<link rel="mask-icon" href="/img/logo/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">

	<?php if ($darkMode):?>
		<link rel="stylesheet" href="/css/appearance/dark-theme.css">
	<?php else:?>
		<link rel="stylesheet" href="/css/appearance/light-theme.css">
	<?php endif;?>

	<link rel="stylesheet" href="/css/order.css">
</head>
<body>

<nav>
	<a href="/">
		<svg class="logo">
			<use xlink:href="/img/sprites.svg#logo"></use>
		</svg>
	</a>
	<div class="order-info-message">Покупка <?= $orderSize ?> товара за <?= $cost ?> ₽</div>
</nav>

<main>
	<?= $content ?>
</main>

</body>
</html>
