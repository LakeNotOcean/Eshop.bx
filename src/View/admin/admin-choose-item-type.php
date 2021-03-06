<?php
/** @var array<ItemType> $itemTypes */

use Up\Core\Router\URLResolver;
use Up\Entity\ItemType;

?>

<link rel="stylesheet" href="/css/add-item.css">
<div class="container">
	<form action="<?= URLResolver::resolve('admin:add-item') ?>" method="get" enctype="multipart/form-data" class="form-add">
		<label for="item-type" class="field">
			<span class="label-title">Тип товара</span>
			<select id="type-data" id="item-type" name="item-type">
				<?php foreach ($itemTypes as $itemType): ?>
					<option value="<?= $itemType->getId() ?>"><?= htmlspecialchars($itemType->getName()) ?></option>
				<?php endforeach; ?>
			</select>
			<a href="<?= URLResolver::resolve('admin:add-item-type') ?>" class="btn btn-normal">Добавить тип</a>
		</label>
		<input type="submit" value="Далее" class="btn btn-normal input">
	</form>
</div>
