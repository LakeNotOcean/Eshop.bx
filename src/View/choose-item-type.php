<?php
$itemTypes = ['Компьютерные мыши', 'Видеокарты', 'Клавиатуры', 'Процессоры'];
?>

<link rel="stylesheet" href="./css/add-item.css">
<form action="/" method="post" enctype="multipart/form-data" class="form-add-item">
	<label for="item-type" class="field">
		<span class="label-title">Тип товара</span>
		<input type="text" list="type-data" id="item-type" name="item-type" placeholder="Выбрать тип товара">
		<span class="arrow"></span>
		<datalist id="type-data">
			<?php foreach ($itemTypes as $itemType):?>
				<option value="<?= $itemType?>"></option>
			<?php endforeach;?>
		</datalist>
		<a href="/addTypeItem">Добавить тип</a>
		<input type="submit" value="Далее">
	</label>
</form>
