<?php
/** @var array<Specification> $specifications */
/** @var int $categoryId */
/** @var bool $isSpecificationDeleted */

use Up\Entity\Specification;

?>

<link rel="stylesheet" href="/css/add-item.css">
<div class="form-container">
	<form action="/admin/deleteSpecification" method="post" enctype="multipart/form-data" class="form-add">
		<label for="category-id" class="field">
			<input name="category-id" value="<?= $categoryId ?>" type="hidden">
			<span class="label-title">Спецификация</span>
			<select id="specification-id" name="specification-id" class="input-spec-name">
				<?php
				foreach ($specifications as $specification): ?>
					<option value="<?= $specification->getId() ?>"><?= $specification->getName() ?></option>
				<?php
				endforeach; ?>
			</select>
		</label>
		<input type="submit" value="Удалить выбранную спецификацию" class="btn-save">
		<div>Внимание! Удаление спецификации приведет к ее удалению со всех товаров!</div>
	</form>
	<?php
	if ($isSpecificationDeleted): ?>
		<div id="popup" class="popup">Спецификация удалена</div>
	<?php
	endif; ?>
</div>

<script src="/js/popup-disappear.js"></script>
