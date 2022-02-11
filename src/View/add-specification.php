<?php
/** @var array<\Up\Entity\SpecificationCategory> $categories */
/** @var bool $isNewSpecAdded */
?>

<link rel="stylesheet" href="/css/add-item.css">
<div class="form-container">
	<form action="/addSpecification" method="post" enctype="multipart/form-data" class="form-add">
		<label for="category-id" class="field">
			<span class="label-title">Категория</span>
			<select id="category-id" name="category-id" class="input-category">
				<?php foreach ($categories as $category):?>
					<option value="<?= $category->getId()?>"><?= $category->getName()?></option>
				<?php endforeach;?>
			</select>
		</label>
		<label for="spec-name" class="field">
			<span class="label-title">Спецификация</span>
			<input type="text" id="spec-name" name="spec-name" placeholder="Введите название спецификации">
		</label>
		<label for="spec-order" class="field">
			<span class="label-title">Порядок отображения</span>
			<input type="number" id="spec-order" name="spec-order" placeholder="Введите порядок отображения спецификации" value="0">
		</label>
		<input type="submit" value="Сохранить спецификацию в базу данных" class="btn-save">
	</form>
	<?php if ($isNewSpecAdded):?>
		<div id="popup" class="popup">Добавлена новая спецификация</div>
	<?php endif;?>
</div>

<script src="/js/popup-disappear.js"></script>
