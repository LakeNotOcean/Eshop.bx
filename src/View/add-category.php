<?php
/** @var bool $isNewCategoryAdded */
?>

<link rel="stylesheet" href="/css/add-item.css">
<div class="form-container">
	<form action="/addCategory" method="post" enctype="multipart/form-data" class="form-add">
		<label for="category" class="field">
			<span class="label-title">Категория</span>
			<input type="text" id="category" name="category" placeholder="Введите название категории">
		</label>
		<label for="category-order" class="field">
			<span class="label-title">Порядок отображения</span>
			<input type="number" id="category-order" name="category-order" placeholder="Введите порядок отображения" value="0">
		</label>
		<input type="submit" value="Сохранить категорию в базу данных" class="btn-save">
	</form>
	<?php if ($isNewCategoryAdded):?>
		<div id="popup" class="popup">Добавлена новая категория</div>
	<?php endif;?>
</div>

<script src="/js/popup-disappear.js"></script>
