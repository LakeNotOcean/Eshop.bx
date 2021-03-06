<?php
/** @var array<SpecificationCategory> $categories */

/** @var string $message */

use Up\Core\Router\URLResolver;
use Up\Entity\SpecificationCategory;
use Up\Lib\CSRF\CSRF;

?>

<link rel="stylesheet" href="/css/add-item.css">
<div class="container">
	<form action="<?= URLResolver::resolve('admin:add-specification') ?>" method="post" enctype="multipart/form-data" class="form-add">
		<label for="category-id" class="field">
			<span class="label-title">Категория</span>
			<select id="category-id" name="category-id" class="input-category">
				<?php
				foreach ($categories as $category): ?>
					<option value="<?= $category->getId() ?>"><?= htmlspecialchars($category->getName()) ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<label for="spec-name" class="field">
			<span class="label-title">Спецификация</span>
			<input type="text" id="spec-name" name="spec-name" placeholder="Введите название спецификации" class="input">
		</label>
		<label for="spec-order" class="field">
			<span class="label-title">Порядок отображения</span>
			<input type="number" id="spec-order" name="spec-order" placeholder="Введите порядок отображения спецификации" value="0"
				   class="input">
		</label>
		<input type="submit" value="Сохранить спецификацию в базу данных" class="btn btn-normal input">
		<?= CSRF::getFormField() ?>
	</form>
	<?php if (!empty($message)):?>
		<div id="popup" class="popup"><?= htmlspecialchars($message)?></div>
	<?php endif;?>
</div>

<script src="/js/lib/popup-disappear.js"></script>
