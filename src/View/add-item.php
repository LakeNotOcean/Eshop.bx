<?php
$itemTypes = ['Компьютерные мыши', 'Видеокарты', 'Клавиатуры', 'Процессоры'];

$categories = ['Заводские данные', 'Внешний вид'];
$specs = ['Гарантия', 'Страна-производитель', 'Основной цвет', 'Дополнительный цвет', 'Подсветка'];

$template = ['Заводские данные'=>['Гарантия', 'Страна-производитель'], 'Внешний вид' => ['Основной цвет', 'Дополнительный цвет', 'Подсветка']];
?>

<!--<link rel="stylesheet" href="./css/add-item.css">-->
<link rel="stylesheet" href="/public/css/add-item.css">

<form action="/" method="post" enctype="multipart/form-data" class="form-add-item">
	<label for="type">Выбрать тип товара
		<input list="type-data" id="item-type" name="item-type" placeholder="Тип товара">
		<datalist id="type-data">
			<?php foreach ($itemTypes as $itemType):?>
				<option value="<?= $itemType?>"></option>
			<?php endforeach;?>
		</datalist>
	</label>

	<label for="title">Название товара
		<input id="title" name="title" placeholder="Ввести название товара">
	</label>

	<label for="price">Стоимость товара
		<input id="price" name="price" placeholder="Ввести стоимость товара">
	</label>

	<label for="short-description">Краткое описание
		<input id="short-description" name="short-description" placeholder="Ввести краткое описание товара">
	</label>

	<label for="full-description">Описание
		<input id="full-description" name="full-description" placeholder="Ввести полное описание товара">
	</label>

	<label for="tags">Теги
		<input id="tags" name="tags" placeholder="Ввести теги через запятую">
	</label>

	<div class="images">
		<div class="images-title">Фотографии</div>
		<div class="main-image">
			<label for="main-image">Фото обложки
				<input type="file" id="main-image" name="main-image" >
			</label>
		</div>
		<div class="other-images">
			<label for="other-images">Порядок фотографий
				<input type="file" id="other-images" name="other-images" multiple="multiple">
			</label>
		</div>
	</div>

	<div class="specifications">
		<div class="specifications-title"></div>

		<?php foreach ($template as $categoryName => $category):?>
			<div class="category">
				<input list="category-data" class="input-category" placeholder="Ввести название категории" value="<?= $categoryName?>">
				<datalist id="category-data">
					<?php foreach ($categories as $categoryData):?>
						<option value="<?= $categoryData?>"></option>
					<?php endforeach;?>
				</datalist>
				<div class="btn-delete">Удалить</div>
			</div>

			<?php foreach ($category as $spec):?>
				<div class="spec">
					<input list="spec-data" placeholder="Ввести название спецификации" value="<?= $spec?>">
					<datalist id="spec-data">
						<?php foreach ($specs as $specData):?>
							<option value="<?= $specData?>"></option>
						<?php endforeach;?>
					</datalist>
					<input type="text" placeholder="Ввести значение спецификации">
					<div class="btn-delete">Удалить</div>
				</div>
			<?php endforeach;?>
			<div class="btn-add">Добавить спецификацию</div>
		<?php endforeach;?>
		<div class="btn-add">Добавить категорию</div>
	</div>

	<input type="submit" value="Сохранить товар в базу данных">
</form>
