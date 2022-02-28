<?php
/** @var bool $isNewCategoryAdded */

?>

<link rel="stylesheet" href="/css/add-item.css">
<div class="container">
	<form action="/admin/addCategory" method="post" enctype="multipart/form-data" class="form-add">
		<label for="category" class="field">
			<span class="label-title">Категория</span>
			<input type="text" id="category" name="category" placeholder="Введите название категории" class="input"
				   autocomplete="off">
		</label>
		<label for="category-order" class="field">
			<span class="label-title">Порядок отображения</span>
			<input type="number" id="category-order" name="category-order" placeholder="Введите порядок отображения" value="0"
				   class="input" autocomplete="off">
		</label>
		<input type="submit" value="Сохранить категорию в базу данных" class="btn btn-normal input">
		<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
	</form>
	<div id="popup" class="popup <?= !$isNewCategoryAdded ? 'hidden' : ''?>">Добавлена новая категория</div>
</div>

<script src="/js/lib/popup-disappear.js"></script>
