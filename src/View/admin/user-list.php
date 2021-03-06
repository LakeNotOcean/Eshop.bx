<?php

/** @var array $users */
/** @var string $paginator */
/** @var string $query */
/** @var int $userAmount */

use Up\Core\Router\URLResolver;
use Up\Lib\CSRF\CSRF;

?>
<link rel="stylesheet" href="/css/users-list.css">

<div class="container">

	<div class="user-line">
		<div class="search-count">
			Найдено пользователей <?= $query !== '' ?  'по запросу ' . htmlspecialchars($query) : ''?>: <?= htmlspecialchars($userAmount)?>
		</div>
		<form action="<?= URLResolver::resolve('admin:user-list')?>" method="get" enctype="multipart/form-data" class="local-search">
			<input type="text" id="query" name="query" class="search-field" placeholder="Поиск пользователей"
				   value="<?= htmlspecialchars($query) ?>">
			<div class="search-icon">
				<div></div>
			</div>
		</form>
	</div>
	<div class="user-list">
		<?php foreach ($users as $user): ?>
			<div class="user  card-outline" draggable="true" id=<?=htmlspecialchars($user->getLogin())?>>
				<div class="user-body">
					<div class="user-info">
						<label>ФИО:</label> <?=htmlspecialchars($user->getFirstName())?>  <?=htmlspecialchars($user->getSecondName())?>
					</div>
					<div class="user-info">
						<label>Логин:</label> <?=htmlspecialchars($user->getLogin())?>
					</div>
					<div class="user-info">
						<label>Email:</label> <?=htmlspecialchars($user->getEmail())?>
					</div>
					<div class="user-info">
						<label>Телефон:</label> <?=htmlspecialchars($user->getPhone())?>
					</div>
					<div class="user-info">
						<label>Роль:</label> <?=htmlspecialchars($user->getRole()->getName())?>
					</div>
				</div>
				<?php if ($user->getRole()->getId() !== 1):?>
				<div class="delete-button-section">
					<a class="btn btn-normal edit-button" href="<?= URLResolver::resolve('admin:user-info', ["id"=>$user->getId()]) ?>" id=<?=htmlspecialchars($user->getLogin())?>>
						<svg class="pencil">
							<use xlink:href="/img/sprites.svg#pencil"></use>
						</svg>
					</a>
				</div>
				<?php endif;?>
			</div>
		<?php endforeach; ?>
	</div>
	<?= CSRF::getFormField() ?>
	<?= $paginator ?>
</div>
<div class="token"></div>

<script src="/js/lib/alert-dialog.js"></script>
<script src="/js/lib/showPopup.js"></script>
<script src="/js/admin-list/get-search.js"></script>
