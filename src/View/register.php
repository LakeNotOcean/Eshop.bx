<?php

use Up\Core\Router\URLResolver;
use Up\Lib\CSRF\CSRF; ?>

<link rel="stylesheet" href="/css/login.css">
<link rel="stylesheet" href="/css/register.css">

<div class="container">
	<div class="sign-in-title">Регистрация</div>
	<form class="register-fields" method="post" action="<?= URLResolver::resolve('register-user') ?>">
		<div class="label-input">
			<span class="label-title">Полное имя</span>
			<div class="input-error">
				<label for="firstName" class="field">
					<input type="text" class="input" id="firstName" name="firstName" placeholder="Полное имя">
				</label>
				<span class="label-error" data-source="firstName">Неверный формат имени</span>
			</div>
		</div>
		<div class="label-input">
			<span class="label-title">Фамилия</span>
			<div class="input-error">
				<label for="secondName" class="field">
					<input type="text" class="input" id="secondName" name="secondName" placeholder="Фамилия">
				</label>
				<span class="label-error" data-source="secondName">Неверный формат фамилии</span>
			</div>
		</div>
		<div class="label-input">
			<span class="label-title">Логин</span>
			<div class="input-error">
				<label for="login" class="field">
					<input type="text" class="input" id="login" name="login" placeholder="Логин">
				</label>
				<span class="label-error" data-source="login">Логин должен состоять из не менее чем 5 символов латинского алфавита и цифр</span>
			</div>
		</div>
		<div class="label-input">
			<span class="label-title">Пароль</span>
			<div class="input-error">
				<label for="login" class="field">
					<input type="password" class="input" id="password" name="password" placeholder="Пароль">
				</label>
				<span class="label-error" data-source="password">Пароль должен состоять из не менее чем 5 символов латинского алфавита и цифр</span>
			</div>
		</div>
		<div class="label-input">
			<span class="label-title">Повторите Пароль</span>
			<div class="input-error">
				<label for="repeatPassword" class="field">
					<input type="password" class="input" id="repeatPassword" name="repeatPassword" placeholder="Повторите пароль">
				</label>
				<span class="label-error" data-source="repeatPassword">Пароли не совпадают</span>
			</div>
		</div>
		<div class="label-input">
			<span class="label-title">Email</span>
			<div class="input-error">
				<label for="email" class="field">
					<input type="email" class="input" id="email" name="email" placeholder="Email">
				</label>
				<span class="label-error" data-source="email">Неверный формат email</span>
			</div>
		</div>
		<div class="label-input">
			<span class="label-title">Контактный телефон</span>
			<div class="input-error">
				<label for="phone" class="field">
					<input type="tel" class="input" id="phone" name="phone" placeholder="Контактный телефон" required/>
				</label>
				<span class="label-error" data-source="phone">Неверный формат телефона</span>
			</div>
		</div>
		<?= CSRF::getFormField() ?>
		<input type="submit" id="submit" value="Зарегистрироваться" class="btn btn-normal input">
	</form>
	<div class="errors-container"></div>
</div>

<script src="/js/user/help-user.js" type="module"></script>
<script src="/js/user/registrate-user.js" type="module"></script>
<script src="/js/lib/phone-input.js"></script>
<script src="/js/lib/send-simple-form.js"></script>
