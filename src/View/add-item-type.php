<?php
/** @var string $message */

use Up\Core\Router\URLResolver;
use Up\Lib\CSRF\CSRF;

?>

<link rel="stylesheet" href="/css/add-item.css">
<div class="container">
	<form action="<?= URLResolver::resolve('admin:add-item-type') ?>" method="post" enctype="multipart/form-data" class="form-add">
		<label for="item-type" class="field">
			<span class="label-title">Тип товара</span>
			<input type="text" id="item-type" name="item-type" placeholder="Введите название типа товара" class="input"
				   autocomplete="off">
		</label>
		<div class="specifications">
			<div class="specifications-title">Характеристики</div>
			<div class="btn btn-add add-category">Добавить категорию</div>
		</div>
		<?= CSRF::getFormField() ?>
		<input type="submit" value="Сохранить тип товара в базу данных" class="btn btn-normal input">
	</form>
	<?php if (isset($message)): ?>
		<div class="popup"><?= htmlspecialchars($message)?></div>
	<?php endif; ?>
</div>

<script src="/js/lib/popup-disappear.js"></script>
<script src="/js/eshop-api.js"></script>
<script src="/js/add-item/build-item-type.js"></script>
