<?php

use Up\Core\Router\URLResolver;
use Up\Lib\CSRF\CSRF;

?>

<link rel="stylesheet" href="/css/change-password.css">

<div class="container">
	<form action="<?= URLResolver::resolve('change-password') ?>" method="post" enctype="multipart/form-data">
		<?= CSRF::getFormField() ?>
		<div class="field">
			<label class="label-title">Действующий пароль</label>
			<input class="input" type="password" name="oldPassword">
		</div>
		<div class="field">
			<label class="label-title">Новый пароль</label>
			<input class="input" type="password" name="newPassword1">
		</div>
		<div class="field">
			<label class="label-title">Повторите пароль</label>
			<input class="input" type="password" name="newPassword2">
		</div>
		<?php if (isset($errors)):?>
			<div class="error-list">
				<?php foreach ($errors as $error):?>
					<li class="error"><?= htmlspecialchars($error)?></li>
				<?php endforeach;?>
			</div>
		<?php endif;?>
		<input class="btn btn-normal input" type="submit" value="Сохранить">
	</form>
</div>
