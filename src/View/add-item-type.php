<?php

$categories = ['Заводские данные', 'Внешний вид'];
$specs = ['Гарантия', 'Страна-производитель', 'Основной цвет', 'Дополнительный цвет', 'Подсветка'];

$template = [''=>['']];
?>

<link rel="stylesheet" href="./css/add-item.css">
<form action="/" method="post" enctype="multipart/form-data" class="form-add-item">
	<label for="item-type">Тип товара
		<input type="text" id="item-type" name="item-type" placeholder="Введите название типа товара">
	</label>
	<div class="specifications">
		<div class="specifications-title">Характеристики</div>

		<?php foreach ($template as $categoryName => $category):?>
			<div class="category">
				<input list="category-data" class="input-category" placeholder="Выбрать название категории" value="<?= $categoryName?>">
				<datalist id="category-data">
					<?php foreach ($categories as $categoryData):?>
						<option value="<?= $categoryData?>"></option>
					<?php endforeach;?>
				</datalist>
				<div class="btn-delete">Удалить</div>
			</div>

			<?php foreach ($category as $spec):?>
				<div class="spec">
					<input list="spec-data" placeholder="Выбрать название спецификации" value="<?= $spec?>">
					<datalist id="spec-data">
						<?php foreach ($specs as $specData):?>
							<option value="<?= $specData?>"></option>
						<?php endforeach;?>
					</datalist>
					<div class="btn-delete">Удалить</div>
				</div>
			<?php endforeach;?>
			<div class="btn-add">Добавить спецификацию</div>
		<?php endforeach;?>
		<div class="btn-add">Добавить категорию</div>
	</div>

	<input type="submit" value="Сохранить тип товара в базу данных" class="btn-save">
</form>
