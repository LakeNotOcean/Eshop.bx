<?php
/** @var string $state */

?>

<!--<link rel="stylesheet" href="./css/login.css">-->
<link rel="stylesheet" href="/css/login.css">

<div class="container">
	<div class="sign-in-title">Вход</div>
	<form action="/login" method="post" enctype="multipart/form-data" class="sign-in-form">
		<input type="text" id="login" name="login" class="input" placeholder="Логин">
		<input type="password" id="password" name="password" class="input" placeholder="Пароль">
		<input type="submit" id="submit-button" class="submit-button" value="login">
	</form>
	<div class='<?php
	if ($state == 'unsuccessful')
	{
		echo 'incorrect-pass-visible';
	} ?> incorrect-pass'>
	</div>
</div>
