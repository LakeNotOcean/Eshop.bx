<?php
/** @var array<\Up\Entity\ItemType> $itemTypes */
?>

<link rel="stylesheet" href="/css/add-item.css">
<link rel="stylesheet" href="/css/choose-item-type.css">
<div class="form-container">
	<form action="/addItem" method="get" enctype="multipart/form-data" class="form-add">
		<label for="item-type" class="field">
			<span class="label-title">Тип товара</span>
			<select id="type-data" id="item-type" name="item-type">
				<?php foreach ($itemTypes as $itemType):?>
					<option value="<?= $itemType->getId()?>"><?= $itemType->getName()?></option>
				<?php endforeach;?>
			</select>
			<a href="/addItemType" class="btn-add-type">Добавить тип</a>
		</label>
		<input type="submit" value="Далее" class="btn-save">
	</form>
</div>
