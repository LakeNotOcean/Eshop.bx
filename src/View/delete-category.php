<?php
/** @var array<SpecificationCategory> $categories */

/** @var bool $isCategoryDeleted */

use Up\Entity\SpecificationCategory;

?>

<link rel="stylesheet" href="/css/add-item.css">
<div class="form-container">
	<form action="/admin/deleteCategory" method="post" enctype="multipart/form-data" class="form-add">
		<label for="category-id" class="field">
			<span class="label-title">Категория</span>
			<select id="category-id" name="category-id" class="input-category">
				<?php
				foreach ($categories as $category): ?>
					<option value="<?= $category->getId() ?>"><?= $category->getName() ?></option>
				<?php
				endforeach; ?>
			</select>
		</label>
		<input type="submit" value="Удалить выбранную категорию" class="btn-save">
		<div>Внимание! Удаление категории приведет к ее удалению со всех товаров!</div>
	</form>
	<?php
	if ($isCategoryDeleted): ?>
		<div id="popup" class="popup">Категория удалена</div>
	<?php
	endif; ?>
</div>

<script src="/js/popup-disappear.js"></script>
