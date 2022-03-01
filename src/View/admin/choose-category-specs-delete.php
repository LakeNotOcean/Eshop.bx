<?php
/** @var array<SpecificationCategory> $categories */

use Up\Entity\SpecificationCategory;

?>

<link rel="stylesheet" href="/css/add-item.css">
<div class="container">
	<div class="form-add">
		<label for="category-id" class="field">
			<span class="label-title">Выберите категорию, в которой нужно удалить спецификацию</span>
			<select id="category-id" name="category-id" class="input-category">
				<?php foreach ($categories as $category): ?>
					<option value="<?= $category->getId() ?>"><?= htmlspecialchars($category->getName()) ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<a class="btn btn-normal input">Выбрать категорию</a>
	</div>
</div>

<script src="/js/add-item/choose-category-delete-script.js"></script>
