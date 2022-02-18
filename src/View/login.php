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
		<label for="login" class="field">
			<div class="label-input">
				<span class="label-title">Логин</span>
				<div class="input-error">
					<input type="text" class="input" id="login" name="login" placeholder="Логин">
					<span class="label-error" data-source="login">Логин должен состоять из не менее чем 5 символов латинского алфавита и цифр</span>
				</div>
			</div>
		</label>
		<label for="login" class="field">
			<div class="label-input">
				<span class="label-title">Пароль</span>
				<div class="input-error">
					<input type="password" class="input" id="password" name="password" placeholder="Пароль">
					<span class="label-error" data-source="password">Пароль должен состоять из не менее чем 5 символов латинского алфавита и цифр</span>
				</div>
			</div>
		</label>
		<input type="submit" value="Войти" class="btn btn-save">
	</form>
</div>