<?php
/** @var string $content */
?>

<!doctype html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>EShop</title>
	<link rel="stylesheet" href="./css/admin-main.css">
</head>
<body>

<nav>
	<div class="start">
		<a href="/">
			<svg class="logo">
				<use xlink:href="./img/sprites.svg#logo"></use>
			</svg>
		</a>
		<div class="nav-item nav-item-active">Добавить товар
			<div class="menu-container">
				<div class="menu">
					<a class="menu-item" href="/">Добавить товар</a>
					<a class="menu-item" href="/">Добавить тип товара</a>
					<a class="menu-item" href="/">Добавить категорию</a>
					<a class="menu-item" href="/">Добавить спецификацию</a>
				</div>
			</div>
		</div>
	</div>
	<div class="sign-out">Выйти</div>
</nav>

<main>
	<?= $content?>
</main>

</body>
</html>
