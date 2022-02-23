<?php

/** @var array $admins */
/** @var string $paginator */

?>
<link rel="stylesheet" href="/css/add-admins.css">

<div class="container">
	<div class="admin-line">
		<form action="/admin/adminList" method="get" enctype="multipart/form-data" class="search">
			<input type="text" id="query" name="query" class="search-field" placeholder="Поиск по сайту"
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
						ФИО: <?=$admin->getFirstName()?>  <?=$admin->getSecondName()?>
					</div>
					<div class="admin-info">
						Логин: <?=$admin->getLogin()?>
					</div>
					<div class="admin-info">
						Email: <?=$admin->getEmail()?>
					</div>
					<div class="admin-info">
						Телефон: <?=$admin->getPhone()?>
					</div>
				</div>
				<div class="delete-button-section">
					<div class="btn btn-normal trash" id=<?=$admin->getLogin()?>>
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
	</div>
	<?= $paginator?>
</div>
<div class="token"></div>
<script src="/js/admin-list/delete-admin.js"></script>
<script src="/js/lib/showPopup.js"></script>
