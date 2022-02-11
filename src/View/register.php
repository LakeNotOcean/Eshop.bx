<?php

?>

<!--<link rel="stylesheet" href="./css/login.css">-->
<link rel="stylesheet" href="/css/login.css">

<div class="container">
	<div class="sign-in-title">Вход</div>
	<form action="/register" method="post" enctype="multipart/form-data" class="sign-in-form">
		<input type="text" id="login" name="login" class="input" placeholder="Логин">
		<input type="password" id="password" name="password" class="input" placeholder="Пароль">
		<input type="text" id="email" name="email" class="input" placeholder="Почта">
		<input type="text" id="phone" name="phone" class="input" placeholder="Телефон">
		<input type="submit" id="submit" class="button" value="Отрпавить">
	</form>
</div>
