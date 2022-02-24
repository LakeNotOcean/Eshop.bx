<?php

/** @var \Up\Entity\User\User $user */
/** @var bool $fromUserList */
?>

<script src="/js/user/user-menu.js"></script>

<link rel="stylesheet" href="/css/user-profile.css">

<div class="container">
	<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
	<div class="main-title">Личная информация<? if ($fromUserList === true){?> пользователя <?= $user->getLogin()?></div>
	<div class="user-info">
		<div class="user-info-header">
			<div class="user-info-name">ID</div>
		</div>
		<div class="user-info-value">
			<div class="user-id"><?= $user->getId()?> </div>
		</div>
	</div>
	<div class="user-info">
		<div class="user-info-header">
			<div class="user-info-name">Login</div>
			<div class="btn-change">Редактировать</div>
		</div>
		<div class="user-info-value">
			<div class="user-login"><?= $user->getLogin()?> </div>
		</div>
		<div class="user-info-change gone">
			<label>
				<input id="user-login" type="text" class="input" placeholder="Логин" value="<?= $user->getLogin() ?>">
			</label>
			<div class="btn btn-normal btn-save">Сохранить</div>
		</div>
	</div><?} else echo "</div> "?>
	<div class="user-info">
		<div class="user-info-header">
			<div class="user-info-name">Имя</div>
			<div class="btn-change">Редактировать</div>
		</div>
		<div class="user-info-value">
			<div class="user-first-name"><?= $user->getFirstName()?> </div>
			<div class="user-second-name"><?= $user->getSecondName()?></div>
		</div>
		<div class="user-info-change gone">
			<label>
				<input id="user-first-name" type="text" class="input" placeholder="Имя" value="<?= $user->getFirstName()?>">
			</label>
			<label>
				<input id="user-second-name" type="text" class="input" placeholder="Фамилия" value="<?= $user->getSecondName()?>">
			</label>
			<div class="btn btn-normal btn-save">Сохранить</div>
		</div>
	</div>
	<div class="user-info">
		<div class="user-info-header">
			<div class="user-info-name">Телефон</div>
			<div class="btn-change">Редактировать</div>
		</div>
		<div class="user-info-value user-phone"><?= $user->getPhone()?></div>
		<div class="user-info-change gone">
			<label>
				<input id="user-phone" type="tel" class="input" placeholder="Телефон" value="<?= $user->getPhone()?>">
			</label>
			<div class="btn btn-normal btn-save">Сохранить</div>
		</div>
	</div>
	<div class="user-info">
		<div class="user-info-header">
			<div class="user-info-name">E-mail</div>
			<div class="btn-change">Редактировать</div>
		</div>
		<div class="user-info-value user-email"><?= $user->getEmail()?></div>
		<div class="user-info-change gone">
			<label>
				<input id="user-email" type="email" class="input" placeholder="E-mail" value="<?= $user->getEmail()?>">
			</label>
			<div class="btn btn-normal btn-save">Сохранить</div>
		</div>
	</div>
	<? if ($fromUserList === true){?>
	<div class="user-info">
		<div class="user-info-header">
			<div class="user-info-name">Права</div>
			<div class="btn-change">Редактировать</div>
		</div>
		<div class="user-info-value user-role"><?= $user->getRole()->getName()?></div>
		<div class="user-info-change gone">
			<label>
				<input id="user-role" type="text" class="input" placeholder="Права" value="<?= $user->getRole()->getName()?>">
			</label>
			<div class="btn btn-normal btn-save">Сохранить</div>
		</div>
	</div>
	<?}?>
	<a class="btn btn-normal" href="<?= \Up\Core\Router\URLResolver::resolve('change-password') ?>">Сменить пароль</a>
</div>

<script src="/js/lib/showPopup.js"></script>
<? if ($fromUserList === true){?>
<?="<script src='/js/admin-list/admin-edit-profile.js'></script>"?>
<?}else{?>
<?="<script src='/js/user/edit-profile.js'></script>"?>
<?}?>
<script src="/js/lib/phone-input.js"></script>
