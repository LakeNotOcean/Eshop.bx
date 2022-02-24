<?php
/** @var string $content */
/** @var bool $darkMode */

/** @var string $query */
/** @var string $userName */
/** @var bool $isAdmin */
/** @var bool $isAuthenticated */

use Up\Core\Router\URLResolver;

if (!isset($query))
{
	$query = '';
}

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
	<meta name="theme-color" content="#333333">

	<?php if ($darkMode):?>
		<link rel="stylesheet" href="/css/appearance/dark-theme.css" id="appearance" class="appearance-dark">
	<?php else:?>
		<link rel="stylesheet" href="/css/appearance/light-theme.css" id="appearance" class="appearance-light">
	<?php endif;?>

	<link rel="stylesheet" href="/css/main.css">
</head>
<body>

<nav>
	<a href="/">
		<svg class="logo">
			<use xlink:href="/img/sprites.svg#logo"></use>
		</svg>
	</a>
	<div class="search">
		<input type="text" id="query" name="query" class="search-field" placeholder="Поиск по сайту" value="<?= $query ?>"
			   autocomplete="off">
		<div class="search-icon">
			<div></div>
		</div>
	</div>

	<?php if ($isAdmin): ?>
		<div class="nav-bar">
			<div class="nav-item">
				<div class="nav-item-label">Товары</div>
				<div class="menu-container">
					<div class="menu">
						<a class="menu-item" href="/admin/">Админский каталог</a>
						<a class="menu-item" href="/admin/chooseItemType" id="/admin/addItem/">Добавить товар</a>
						<a class="menu-item" href="/admin/addItemType">Добавить тип товара</a>
						<a class="menu-item" href="/admin/addCategory">Добавить категорию</a>
						<a class="menu-item" href="/admin/addSpecification">Добавить спецификацию</a>
						<a class="menu-item" href="/admin/deleteCategory">Удалить категорию</a>
						<a class="menu-item" href="/admin/chooseCategory" id="/admin/deleteSpec/">Удалить спецификацию</a>
					</div>
				</div>
			</div>
			<div class="nav-item">
				<div class="nav-item-label">Заказы</div>
				<div class="menu-container">
					<div class="menu">
						<a class="menu-item" href="/admin/getOrders">Список заказов</a>
					</div>
				</div>
			</div>
			<div class="nav-item">
				<div class="nav-item-label">Администрирование</div>
				<div class="menu-container">
					<div class="menu">
						<a class="menu-item" href="/admin/adminList">Список администраторов</a>
						<a class="menu-item" href="/admin/userList">Список пользователей</a>
					</div>
				</div>
			</div>
			<script src="/js/admin-menu.js"></script>
		</div>
	<?php endif;?>

	<?php if ($isAuthenticated): ?>
		<div class="nav-item" id="userMenu">
			<a href="<?= URLResolver::resolve('make-order') ?>">
				<svg class="cart">
					<use xlink:href="/img/sprites.svg#cart"></use>
				</svg>
				Корзина
			</a>
		</div>

		<div class="nav-item" id="userMenu">
			<div class="nav-item-label"><?= $userName ?></div>
			<div class="menu-container profile">
				<div class="menu">
					<a class="menu-item" href="<?= URLResolver::resolve('user-favorites') ?>">Избранное</a>
					<a class="menu-item" href="<?= URLResolver::resolve('user-profile') ?>">Личный кабинет</a>
					<a href="<?= URLResolver::resolve('logout-user') ?>" class="menu-item-btn">
						<div class="btn btn-normal sign-in">Выйти</div>
					</a>
				</div>
			</div>
		</div>
	<?php else: ?>
		<a href="<?= URLResolver::resolve('make-order') ?>">
			<svg class="cart">
				<use xlink:href="/img/sprites.svg#cart"></use>
			</svg>
		</a>
		<a href="<?= URLResolver::resolve('login-user') ?>">
			<div class="btn btn-normal sign-in">Войти</div>
		</a>
		<a href="<?= URLResolver::resolve('register-user') ?>">
			<div class="btn btn-normal sign-in">Зарегистрироваться</div>
		</a>
	<?php endif; ?>
	<div title="Переключить тему">
		<svg class="btn-theme">
			<?php if ($darkMode):?>
				<use xlink:href="/img/sprites.svg#sun"></use>
			<?php else:?>
				<use xlink:href="/img/sprites.svg#moon"></use>
			<?php endif;?>
		</svg>
	</div>
	<script src="/js/change-theme.js"></script>
</nav>

<main>
	<div class="center">
		<?= $content ?>
	</div>

	<div class="footer">
		© 2022, EShop Inc. · <a href="/">Главная страница</a> · <a href="/">Помощь</a> · <a href="/">Поддержка</a>
	</div>
</main>

</body>
</html>
