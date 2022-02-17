<?php

?>

<!--<link rel="stylesheet" href="./css/login.css">-->
<link rel="stylesheet" href="/css/login.css">

<div class="container">
	<div class="sign-in-title">Регистрация</div>
	<div class="register-fields">
		<label for="login" class="field">
			<div class="label-input">
				<span class="label-title">Полное имя</span>
				<div class="input-error">
					<input type="text" class="input" id="firstName" name="firstName" placeholder="Полное имя">
					<span class="label-error" data-source="firstName">Неверный формат имени</span>
				</div>
			</div>
		</label>
		<label for="login" class="field">
			<div class="label-input">
				<span class="label-title">Фамилия</span>
				<div class="input-error">
					<input type="text" class="input" id="secondName" name="SecondName" placeholder="Фамилия">
					<span class="label-error" data-source="secondName">Неверный формат фамилии</span>
				</div>
			</div>
		</label>
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
		<label for="login" class="field">
			<div class="label-input">
				<span class="label-title">Повторите Пароль</span>
				<div class="input-error">
					<input type="password" class="input" id="repeatPassword" name="repeatPassword" placeholder="Повторите пароль">
					<span class="label-error" data-source="repeatPassword">Пароли не совпадают</span>
				</div>
			</div>
			<label for="login" class="field">
				<div class="label-input">
					<span class="label-title">Email</span>
					<div class="input-error">
						<input type="email" class="input" id="email" name="email" placeholder="Email">
						<span class="label-error" data-source="email">Неверный формат email</span>
					</div>
				</div>
			</label>
		</label>
		<label for="login" class="field">
			<div class="label-input">
				<span class="label-title">Контактный телефон</span>
				<div class="input-error">
					<input type="tel" class="input" id="phone" name="phone" placeholder="Контактный телефон" required/>
					<span class="label-error" data-source="phone">Неверный формат телефона</span>
				</div>
			</div>
		</label>
		<input type="submit" id="submit" value="Зарегестрироваться" class="btn btn-save">
	</div>

</div>

<script src="/js/user/help-user.js" type="module"></script>
<script src="/js/user/registrate-user.js" type="module"></script>
