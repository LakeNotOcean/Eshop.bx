<link rel="stylesheet" href="/css/change-password.css">


<div class="container">
	<form action="<?= \Up\Core\Router\URLResolver::resolve('change-password') ?>" method="post" enctype="multipart/form-data">
		<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
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
		<?php
		if (isset($errors))
		{
			echo "<div class='error-list'>";
			foreach ($errors as $error)
			{
				echo "<li class='error'>". htmlspecialchars($error) . "</li>";
			}
			echo "</div>";
		}
		?>
		<input class="btn btn-normal input" type="submit" value="Сохранить">
	</form>
</div>