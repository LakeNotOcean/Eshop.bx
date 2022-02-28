<?php
/** @var array<Specification> $specifications */
/** @var int $categoryId */
/** @var bool $isSpecificationDeleted */

use Up\Core\Router\URLResolver;
use Up\Entity\Specification;
use Up\Lib\CSRF\CSRF;

?>

<link rel="stylesheet" href="/css/add-item.css">
<div class="container">
	<form action="<?= URLResolver::resolve('admin:delete-specification') ?>" method="post" enctype="multipart/form-data" class="form-add">
		<label for="category-id" class="field">
			<input name="category-id" value="<?= $categoryId ?>" type="hidden">
			<span class="label-title">Спецификация</span>
			<select id="specification-id" name="specification-id" class="input-spec-name">
				<?php
				foreach ($specifications as $specification): ?>
					<option value="<?= $specification->getId() ?>"><?= htmlspecialchars($specification->getName()) ?></option>
				<?php
				endforeach; ?>
			</select>
		</label>
		<input type="submit" value="Удалить выбранную спецификацию" class="btn btn-delete input">
		<?= CSRF::getFormField() ?>
		<div>Внимание! Удаление спецификации приведет к ее удалению со всех товаров!</div>
	</form>
	<?php if ($isSpecificationDeleted): ?>
		<div id="popup" class="popup">Спецификация удалена</div>
	<?php endif; ?>
</div>

<script src="/js/lib/popup-disappear.js"></script>
