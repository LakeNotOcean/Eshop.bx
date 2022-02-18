<?php
/** @var string $state */
/** @var array $errors */
/** @var string $next */
?>

<!--<link rel="stylesheet" href="./css/login.css">-->
<link rel="stylesheet" href="/css/login.css">

<div class="container">
	<div class="sign-in-title">Авторизация</div>
	<form class="register-fields" method="post" action="<?= \Up\Core\Router\URLResolver::resolve('login-user') ?><?= $next ?>">
		<div class="label-input">
			<div class="label-title">Логин</div>
			<div class="input-error">
				<label for="login" class="field">
					<input type="text" class="input" id="login" name="login" placeholder="Логин">
				</label>
				<span class="label-error" data-source="login">Логин должен состоять из не менее чем 5 символов латинского алфавита и цифр</span>
			</div>
		</div>
		<div class="label-input">
			<div class="label-title">Пароль</div>
			<div class="input-error">
				<label for="password" class="field">
					<input type="password" class="input" id="password" name="password" placeholder="Пароль">
				</label>
				<span class="label-error" data-source="password">Пароль должен состоять из не менее чем 5 символов латинского алфавита и цифр</span>
			</div>
		</div>
		<input type="submit" id="submit" value="Войти" class="btn btn-normal">
	</form>
</div>