<?php

$allCategories = [1 => new \Up\Entity\SpecificationCategory(
	1,'Заводские данные',0, [
		new \Up\Entity\Specification(1, 'Гарантия', 0),
		new \Up\Entity\Specification(2, 'Страна-производитель', 1),
	]
), new \Up\Entity\SpecificationCategory(
	2,'Внешний вид',1, [
		 new \Up\Entity\Specification(3, 'Основной цвет', 0),
		 new \Up\Entity\Specification(4, 'Дополнительный цвет', 1),
		 new \Up\Entity\Specification(5, 'Подсветка', 2),
	 ]
)];

$template = $allCategories;
?>

<link rel="stylesheet" href="/css/add-item.css">
<form action="/" method="post" enctype="multipart/form-data" class="form-add-item">
	<datalist id="category-data">
		<?php foreach ($allCategories as $categoryId => $category):?>
			<option value="<?= $category->getName()?>"></option>
		<?php endforeach;?>
	</datalist>
	<?php foreach ($allCategories as $categoryId => $category):?>
		<datalist id="<?= $category->getName()?>-spec-data">
			<?php foreach ($category->getSpecificationList() as $specId => $spec):?>
				<option value="<?= $spec->getName()?>"></option>
			<?php endforeach;?>
		</datalist>
	<?php endforeach;?>

	<div class="main-fields-and-images">
		<div class="main-fields">
			<label for="item-title" class="field">
				<span class="label-title">Название товара</span>
				<input type="text" id="item-title" name="item-title" placeholder="Ввести название товара">
			</label>

			<label for="item-price" class="field">
				<span class="label-title">Стоимость товара</span>
				<input type="text" id="item-price" name="item-price" placeholder="Ввести стоимость товара">
			</label>

			<label for="item-short-description" class="field">
				<span class="label-title">Краткое описание</span>
				<input type="text" id="item-short-description" name="item-short-description" placeholder="Ввести краткое описание товара">
			</label>

			<label for="item-full-description" class="field">
				<span class="label-title">Описание</span>
				<input type="text" id="item-full-description" name="item-full-description" placeholder="Ввести полное описание товара">
			</label>

			<label for="item-tags" class="field">
				<span class="label-title">Теги</span>
				<input type="text" id="item-tags" name="item-tags" placeholder="Ввести теги через запятую">
			</label>
		</div>

		<div class="images">
			<div class="images-title">Фотографии</div>
			<div class="main-image">
				<div class="input-image-header">
					<span class="label-title">Фото обложки</span>
					<input type="file" accept=".webp" id="main-image" name="main-image" class="upload-image">
					<label for="main-image" class="btn-change">Изменить</label>
				</div>
				<div id="main-image-preview" class="preview">
					<img src="/img/default_image.webp" alt="main-image" class="image-img">
				</div>
			</div>
			<div class="other-images">
				<div class="input-image-header">
					<span class="label-title">Порядок фотографий</span>
					<input type="file" multiple="multiple" accept=".webp" id="other-images" name="other-images" class="upload-image">
					<label for="other-images" class="btn-change">Изменить</label>
				</div>
				<div id="other-images-preview" class="preview">
					<div class="no-images-title">Добавьте дополнительные фотографии для товара</div>
				</div>
			</div>
		</div>
	</div>

	<div class="specifications">
		<div class="specifications-title">Характеристики</div>

		<?php foreach ($template as $categoryId => $category):?>
			<div class="category">
				<div class="category-field">
					<div class="field">
						<input type="text" list="category-data" class="input-category" placeholder="Выбрать название категории" value="<?= $category->getName()?>">
						<span class="arrow"></span>
					</div>
					<div class="btn delete">Удалить</div>
				</div>

				<?php foreach ($category->getSpecificationList() as $specId => $spec):?>
					<div class="spec">
						<div class="field">
							<input type="text" list="<?= $category->getName()?>-spec-data" class="input-spec-name" name="specs[<?= $specId?>]" placeholder="Выбрать название спецификации" value="<?= $spec->getName()?>">
							<span class="arrow"></span>
						</div>
						<input type="text" placeholder="Ввести значение спецификации">
						<div class="btn delete">Удалить</div>
					</div>
				<?php endforeach;?>
				<div class="btn add">Добавить спецификацию</div>
			</div>
		<?php endforeach;?>

		<div class="btn add add-category">Добавить категорию</div>
	</div>

	<input type="submit" value="Сохранить товар в базу данных" class="btn save">
</form>
<script src="./js/preview-images.js"></script>
<script src="./js/build-specs.js"></script>
<script src="./js/listen-category.js"></script>
