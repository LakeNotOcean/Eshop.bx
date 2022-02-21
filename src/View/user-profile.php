<?php

/** @var \Up\Entity\User\User $user */

?>

<link rel="stylesheet" href="/css/user-profile.css">

<div class="container">
	<div class="main-title">Личная информация</div>
	<div class="user-info">
		<div class="user-info-header">
			<div class="user-info-name">Имя</div>
			<div class="btn-change">Редактировать</div>
		</div>
		<div class="user-info-value"><?= $user->getName()?></div>
	</div>
	<div class="user-info">
		<div class="user-info-header">
			<div class="user-info-name">Телефон</div>
			<div class="btn-change">Редактировать</div>
		</div>
		<div class="user-info-value"><?= $user->getPhone()?></div>
	</div>
	<div class="user-info">
		<div class="user-info-header">
			<div class="user-info-name">E-mail</div>
			<div class="btn-change">Редактировать</div>
		</div>
		<div class="user-info-value"><?= $user->getEmail()?></div>
	</div>
</div>
