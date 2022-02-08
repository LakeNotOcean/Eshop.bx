<?php
$categories = ['Заводские данные', 'Внешний вид'];
?>

<link rel="stylesheet" href="./css/add-item.css">
<form action="/" method="post" enctype="multipart/form-data" class="form-add-item">
	<label for="category">Категория
		<input list="category-data" id="category" name="category" placeholder="Выберите категорию">
		<datalist id="category-data">
			<?php foreach ($categories as $category):?>
				<option value="<?= $category?>"></option>
			<?php endforeach;?>
		</datalist>
	</label>
	<label for="spec">Спецификация
		<input type="text" id="spec" name="spec" placeholder="Введите название спецификации">
	</label>
	<input type="submit" value="Сохранить спецификацию в базу данных">
</form>
