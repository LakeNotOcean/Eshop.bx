<?php
/** @var \Up\Entity\ItemDetail $item */

use Up\Entity\ItemsTag;

?>

<link rel="stylesheet" href="/css/add-item.css">
<div class="container">
	<form enctype="multipart/form-data" class="form-add">
		<div class="main-fields-and-images">
			<div class="main-fields">
				<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
				<?php if (isset($item)):?>
					<input type="hidden" name="item-id" value="<?= $item->getId() ?> ">
					<input type="hidden" name="item-type" value="<?= $item->getItemType()->getId() ?>">
				<?php endif;?>
				<?php if (isset($item)): ?>
					<label for="item-is-active" class="field">
						<span class="label-title">Изменить отображение в каталоге</span>
						<?php if ($item->getIsActive()): ?>
							<div class="btn btn-deactivate">Скрыть</div>
						<?php else: ?>
							<div class="btn btn-return">Вернуть</div>
						<?php endif; ?>
					</label>
				<?php endif; ?>

				<label for="item-title" class="field">
					<span class="label-title">Название товара</span>
					<input type="text" id="item-title" name="item-title" placeholder="Ввести название товара"
						   value="<?= isset($item) ? htmlspecialchars($item->getTitle()) : '' ?>" class="input"
						   autocomplete="off">
				</label>

				<label for="item-price" class="field">
					<span class="label-title">Стоимость товара</span>
					<input type="number" id="item-price" name="item-price" placeholder="Ввести стоимость товара"
						   value="<?= isset($item) ? $item->getPrice() : '' ?>" class="input"
						   autocomplete="off">
				</label>

				<label for="item-short-description" class="field">
					<span class="label-title">Краткое описание</span>
					<input type="text" id="item-short-description" name="item-short-description" placeholder="Ввести краткое описание товара"
						   value="<?= isset($item) ? htmlspecialchars($item->getShortDescription()) : '' ?>" class="input"
						   autocomplete="off">
				</label>

				<label for="item-full-description" class="field">
					<span class="label-title">Описание</span>
					<input type="text" id="item-full-description" name="item-full-description" placeholder="Ввести полное описание товара"
						   value="<?= isset($item) ? htmlspecialchars($item->getFullDescription()) : '' ?>" class="input"
						   autocomplete="off">
				</label>

				<label for="item-tags" class="field">
					<span class="label-title">Теги</span>
					<span class="tags-container">
						<?php if (isset($item)):?>
							<?php foreach ($item->getTags() as $tag):?>
								<span class="input-tag"><?= htmlspecialchars($tag->getName())?></span>
							<?php endforeach;?>
						<?php endif;?>
						<input type="text" id="item-tags" name="item-tags" placeholder="Ввести тег"
							   autocomplete="off">
					</span>
				</label>

				<label for="item-sort_order" class="field">
					<span class="label-title">Порядок сортировки</span>
					<input type="number" id="item-sort_order" name="item-sort_order" placeholder="Ввести порядок сортировки"
						   value="<?= isset($item) ? $item->getSortOrder() : '' ?>" class="input"
						   autocomplete="off">
				</label>
			</div>

			<div class="images">
				<div class="images-title">Фотографии</div>
				<div class="main-image">
					<div class="input-image-header">
						<span class="label-title">Основная фотография товара</span>
						<input type="file" accept="image/*" id="main-image" name="main-image" class="upload-image">
						<label for="main-image" class="btn-change">Изменить</label>
					</div>
					<div id="main-image-preview" class="preview">
						<?php if (isset($item) && $item->getMainImage() !== null): ?>
						<div class="image-container">
							<img src="<?= '/' . $item->getMainImage()->getPath('medium', 'jpeg') ?>" alt="main-image" class="image-img" name="<?=$item->getMainImage()->getId()?>">
							<div class="image-remove-btn"></div>
						</div>
						<?php else: ?>
						<div class="image-container">
							<img src="/img/default_image.webp" alt="main-image" class="image-img">
							<div class="image-remove-btn"></div>
						</div>
						<?php endif;?>
					</div>
				</div>
				<div class="other-images">
					<div class="input-image-header">
						<span class="label-title">Дополнительные фотографии</span>
						<input type="file" multiple="multiple" accept="image/*" id="other-images" name="other-images" class="upload-image">
						<label for="other-images" class="btn-change">Изменить</label>
					</div>
					<div id="other-images-preview" class="preview">
						<?php
						if (isset($item) && count($item->getImages()) > 0): ?>
						<?php foreach ($item->getImages() as $image): ?>
							<?php if (!$image->isMain()): ?>
									<div class="image-container">
										<img src="<?= '/' . $image->getPath('medium', 'jpeg') ?>" alt="other-image" class="image-img old-img" name="<?=$image->getId()?>">
										<div class="image-remove-btn"></div>
									</div>
							<?php endif; ?>
						<?php endforeach; ?>
						<?php else: ?>
						<div class="no-images-title">Добавьте дополнительные фотографии для товара</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="specifications">
			<div class="specifications-title">Характеристики</div>
			<div class="btn btn-add add-category">Добавить категорию</div>
		</div>

		<input type="submit" value="Сохранить товар в базу данных" class="btn btn-normal input">
		<?php if (isset($item)): ?>
		<div class="btn btn-delete btn-delete-item">Удалить товар</div>
		<?php endif; ?>
	</form>
</div>
<script src="/js/lib/showPopup.js"></script>
<script src="/js/lib/alert-dialog.js"></script>
<script src="/js/add-item/preview-images.js"></script>
<script src="/js/eshop-api.js"></script>
<script src="/js/lib/input-tags.js"></script>
<script src="/js/add-item/build-specs.js"></script>
<script src="/js/add-item/add-item-script.js"></script>
<script src="/js/deactivate-item.js"></script>
