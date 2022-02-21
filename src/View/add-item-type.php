<?php
/** @var bool $isNewItemTypeAdded */

?>

<link rel="stylesheet" href="/css/add-item.css">
<div class="form-container">
	<form action="/admin/addItemType" method="post" enctype="multipart/form-data" class="form-add">
		<label for="item-type" class="field">
			<span class="label-title">Тип товара</span>
			<input type="text" id="item-type" name="item-type" placeholder="Введите название типа товара" class="input">
		</label>
		<div class="specifications">
			<div class="specifications-title">Характеристики</div>
			<div class="btn btn-add add-category">Добавить категорию</div>
		</div>
		<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
		<input type="submit" value="Сохранить тип товара в базу данных" class="btn-normal input">
	</form>
	<?php
	if ($isNewItemTypeAdded): ?>
		<div class="popup">Добавлен новый тип товара</div>
	<?php
	endif; ?>
</div>

<script src="/js/lib/popup-disappear.js"></script>
<script src="/js/eshop-api.js"></script>
<script src="/js/add-item/build-item-type.js"></script>
