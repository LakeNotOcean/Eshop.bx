<?php
/** @var bool $isNewItemTypeAdded */

?>

<link rel="stylesheet" href="/css/add-item.css">
<div class="form-container">
	<form action="/addItemType" method="post" enctype="multipart/form-data" class="form-add">
		<label for="item-type" class="field">
			<span class="label-title">Тип товара</span>
			<input type="text" id="item-type" name="item-type" placeholder="Введите название типа товара">
		</label>
		<div class="specifications">
			<div class="specifications-title">Характеристики</div>
			<div class="btn add add-category">Добавить категорию</div>
		</div>

		<input type="submit" value="Сохранить тип товара в базу данных" class="btn-save">
	</form>
	<?php
	if ($isNewItemTypeAdded): ?>
		<div id="popup" class="popup">Добавлен новый тип товара</div>
	<?php
	endif; ?>
</div>
<script src="/js/popup-disappear.js"></script>
<script src="/js/build-item-type.js"></script>
