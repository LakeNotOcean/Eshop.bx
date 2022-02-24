<?php

/** @var array $admins */
/** @var string $paginator */
/** @var string $query */
?>
<link rel="stylesheet" href="/css/admin-list.css">

<div class="container">
	<div class="admin-line">
		<form action="/admin/adminList" method="get" enctype="multipart/form-data" class="search">
			<input type="text" id="query" name="query" class="search-field" placeholder="Поиск администраторов"
				   value="<?= htmlspecialchars($query)?>">
			<div class="search-icon">
				<div></div>
			</div>
		</form>
	</div>
	<div class="admin-list">
		<? foreach ($admins as $admin){?>
			<div class="admin  card-outline" draggable="true" id=<?=$admin->getLogin()?>>
				<div class="admin-body">
					<div class="admin-info">
						<label>ФИО:</label> <?=htmlspecialchars($admin->getFirstName())?>  <?=htmlspecialchars($admin->getSecondName())?>
					</div>
					<div class="admin-info">
						<label>Логин:</label> <?=htmlspecialchars($admin->getLogin())?>
					</div>
					<div class="admin-info">
						<label>Email:</label> <?=htmlspecialchars($admin->getEmail())?>
					</div>
					<div class="admin-info">
						<label>Телефон:</label> <?=htmlspecialchars($admin->getPhone())?>
					</div>
				</div>
				<div class="delete-button-section">
					<div class="btn btn-normal trash" id=<?=htmlspecialchars($admin->getLogin())?>>
						<svg class="trash-icon">
							<use xlink:href="/img/sprites.svg#trash"></use>
						</svg>
					</div>
				</div>

			</div>
		<?}?>
	</div>
	<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
	<div class="delete-section card-outline">
		<div class="delete-section-text">
			Перетащи сюда чтобы удалить
		</div>
	</div>
	<?= $paginator?>
</div>
<div class="token"></div>

<script src="/js/admin-list/delete-admin.js"></script>
<script src="/js/lib/showPopup.js"></script>
