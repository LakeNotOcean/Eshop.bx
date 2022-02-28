<?php
/** @var array<SpecificationCategory> $categories */

/** @var bool $isCategoryDeleted */

use Up\Core\Router\URLResolver;
use Up\Entity\SpecificationCategory;
use Up\Lib\CSRF\CSRF;

?>

<link rel="stylesheet" href="/css/add-item.css">
<div class="container">
	<form action="<?= URLResolver::resolve('admin:delete-category') ?>" method="post" enctype="multipart/form-data" class="form-add">
		<label for="category-id" class="field">
			<span class="label-title">Категория</span>
			<select id="category-id" name="category-id" class="input-category">
				<?php
				foreach ($categories as $category): ?>
					<option value="<?= $category->getId() ?>"><?= htmlspecialchars($category->getName()) ?></option>
				<?php
				endforeach; ?>
			</select>
		</label>
		<input type="submit" value="Удалить выбранную категорию" class="btn btn-delete input">
		<?= CSRF::getFormField() ?>
		<div>Внимание! Удаление категории приведет к ее удалению со всех товаров!</div>
	</form>
	<?php
	if ($isCategoryDeleted): ?>
		<div id="popup" class="popup">Категория удалена</div>
	<?php
	endif; ?>
</div>

<script src="/js/lib/popup-disappear.js"></script>
