<?php

/** @var \Up\Entity\User\User $user */
/** @var bool $fromUserList */
/** @var array $roles */
?>

<script src="/js/user/user-menu.js"></script>

<link rel="stylesheet" href="/css/user-profile.css">

<div class="container">
	<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
	<div class="main-title">Личная информация <?php if (isset($fromUserList)): ?>
	пользователя <?= $user->getLogin()?></div>
	<?php else: ?>
</div>
<?php endif; ?>
	<div class="user-info user-id" id=<?= $user->getId() ?>>
		<div class="user-info-header">
			<div class="user-info-name">Имя</div>
			<div class="btn-change">Редактировать</div>
		</div>
		<div class="user-info-value">
			<div class="user-first-name"><?= htmlspecialchars($user->getFirstName()) ?> </div>
			<div class="user-second-name"><?= htmlspecialchars($user->getSecondName()) ?></div>
		</div>
		<div class="user-info-change gone">
			<label>
				<input id="user-first-name" type="text" class="input" placeholder="Имя" value="<?= htmlspecialchars($user->getFirstName()) ?>">
			</label>
			<label>
				<input id="user-second-name" type="text" class="input" placeholder="Фамилия" value="<?= htmlspecialchars($user->getSecondName()) ?>">
			</label>
			<div class="btn btn-normal btn-save">Сохранить</div>
		</div>
	</div>
	<div class="user-info">
		<div class="user-info-header">
			<div class="user-info-name">Телефон</div>
			<div class="btn-change">Редактировать</div>
		</div>
		<div class="user-info-value user-phone"><?= $user->getPhone() ?></div>
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
	<?php if (isset($fromUserList)): ?>
	<div class="user-info">
		<div class="user-info-header">
			<div class="user-info-name">Права</div>
			<div class="btn-change">Редактировать</div>
		</div>
		<div class="user-info-value user-role"><?= $user->getRole()->getName() ?></div>
		<div class="user-info-change gone">
			<label>
				<select id="user-role">
					<option disabled>Выберите роль</option>
					<?php foreach ($roles as $role): ?>
					<option <?php if ($user->getRole()->getName() === $role->getName()) { echo "selected";} ?> value=<?= $role->getId() ?>><?= $role->getName() ?></option>
					<?php endforeach; ?>
				</select>
			</label>
			<div class="btn btn-normal btn-save btn-save-role">Сохранить</div>
		</div>
	</div>
	<?php endif; ?>
	<a class="btn btn-normal" href="<?= \Up\Core\Router\URLResolver::resolve('change-password') ?>">Сменить пароль</a>
</div>

<script src="/js/lib/showPopup.js"></script>
<?php if (isset($fromUserList)): ?>
<script src='/js/admin-list/admin-edit-profile.js'></script>
<?php else: ?>
<script src='/js/user/edit-profile.js'></script>
<?php endif; ?>
<script src="/js/lib/phone-input.js"></script>
