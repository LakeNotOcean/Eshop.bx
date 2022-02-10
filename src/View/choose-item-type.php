<?php
$itemTypes = ['Компьютерные мыши', 'Видеокарты', 'Клавиатуры', 'Процессоры'];
?>

<link rel="stylesheet" href="/css/add-item.css">
<link rel="stylesheet" href="/css/choose-item-type.css">
<form action="/addItem" method="get" enctype="multipart/form-data" class="form-choose-type">
	<label for="item-type" class="field">
		<span class="label-title">Тип товара</span>
		<input type="text" list="type-data" id="item-type" name="item-type" placeholder="Выбрать тип товара">
		<datalist id="type-data">
			<?php foreach ($itemTypes as $itemType):?>
				<option value="<?= $itemType?>"></option>
			<?php endforeach;?>
		</datalist>
		<a href="/addItemType" class="btn-add-type">Добавить тип</a>
	</label>
	<input type="submit" value="Далее" class="btn-save">
</form>
