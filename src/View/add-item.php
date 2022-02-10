<?php

?>

<link rel="stylesheet" href="/css/add-item.css">
<form action="/testPost" method="post" enctype="multipart/form-data" class="form-add-item">
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
		<div class="btn add add-category">Добавить категорию</div>
	</div>

	<input type="submit"  value="Сохранить товар в базу данных" class="btn save">
</form>
<script src="./js/preview-images.js"></script>
<script src="./js/build-specs.js"></script>
<script src="./js/listen-category.js"></script>
