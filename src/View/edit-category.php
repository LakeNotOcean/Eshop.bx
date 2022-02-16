<?php
/** @var bool $isNewItemTypeAdded */

?>

<link rel="stylesheet" href="/css/add-item.css">
<div class="form-container">
	<form action="/admin/addItemType" method="post" enctype="multipart/form-data" class="form-add">
		<div class="specifications">
			<div class="specifications-title">Характеристики</div>
			<div class="category">
				<div class="category-field">
					<div class="field">
						<select class="input-category">
						</select>
					</div>
					<div class="btn delete">Удалить</div>
				</div>
				<div class="btn add">Добавить спецификацию</div>
			</div>
		</div>

		<input type="submit" value="Сохранить тип товара в базу данных" class="btn-save">
	</form>
	<?php
	if ($isNewItemTypeAdded): ?>
		<div class="popup">Добавлен новый тип товара</div>
	<?php
	endif; ?>
</div>
<script src="/js/popup-disappear.js"></script>
<script src="/js/eshop-api.js"></script>
<script src="/js/build-item-type.js"></script>