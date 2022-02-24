<?php

/** @var array $users */
/** @var string $paginator */
/** @var string $query */
/** @var int $userAmount */
?>
<link rel="stylesheet" href="/css/users-list.css">

<div class="container">

	<div class="user-line">
		<div class="search-count">
			Найдено пользователей<? if ($query !== ''){?> по запросу "<?=$query?>"
			<?}?>: <?=htmlspecialchars($userAmount)?>
		</div>
		<form action="/admin/userList" method="get" enctype="multipart/form-data" class="search">
			<input type="text" id="query" name="query" class="search-field" placeholder="Поиск пользователей"
				   value="<?= htmlspecialchars($query) ?>">
			<div class="search-icon">
				<div></div>
			</div>
		</form>
	</div>
	<div class="user-list">
		<?
		foreach ($users as $user) { ?>
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
				<?if ($user->getRole()->getId() !== 1){?>
				<div class="delete-button-section">
					<div class="btn btn-normal add-admin" id=<?=htmlspecialchars($user->getLogin())?>>
						Сделать администратором
					</div>
				</div>
				<?}?>
			</div>
		<?
		} ?>
	</div>
	<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
	<?= $paginator ?>
</div>
<div class="token"></div>
<script src="/js/admin-list/add-admin.js"></script>
<script src="/js/lib/showPopup.js"></script>