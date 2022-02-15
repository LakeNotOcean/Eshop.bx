<?php
/** @var array<SpecificationCategory> $categories */

use Up\Entity\SpecificationCategory;

?>

<link rel="stylesheet" href="/css/add-item.css">
<div class="form-container">
	<label for="category-id" class="field">
		<span class="label-title">Выберите категорию, в которой нужно удалить спецификацию</span>
		<select id="category-id" name="category-id" class="input-category">
			<?php
			foreach ($categories as $category): ?>
				<option value="<?= $category->getId() ?>"><?= $category->getName() ?></option>
			<?php
			endforeach; ?>
		</select>
	</label>
	<a href="" class="btn-save">Выбрать категорию</a>
</div>

<script src="/js/choose-category-delete-script.js"></script>
