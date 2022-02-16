<?php
/** @var string $content */
/** @var string $query */

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
	<link rel="stylesheet" href="/css/main.css">
</head>
<body>

<nav>
	<a href="/">
		<svg class="logo">
			<use xlink:href="/img/sprites.svg#logo"></use>
		</svg>
	</a>
	<form action="/" method="get" enctype="multipart/form-data" class="search">
		<input type="text" id="query" name="query" class="search-field" placeholder="Поиск по сайту" value="<?= $query ?>">
		<div class="search-icon">
			<div></div>
		</div>
	</form>
	<div class="btn btn-normal sign-in">Войти</div>
</nav>

<main>
	<?= $content ?>
	<div class="footer">
		2022, EShop Inc. · <a href="/">Главная страница</a> · <a href="/">Помощь</a> · <a href="/">Поддержка</a>
	</div>
</main>

</body>
</html>
