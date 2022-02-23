<?php
/** @var array<UP\Entity\UserItem> $items */
/** @var array<Up\Entity\SpecificationCategory> $categories */
/** @var array<Up\Entity\ItemsTag> $tags */
/** @var array $price */
/** @var int $result_count */
/** @var int $itemsAmount */
/** @var string $query */
/** @var string $sortingMethod */
/** @var bool $isAdmin */

/** @var $paginator */

$pref = '_big';

use Up\Core\Router\URLResolver;
use Up\Entity\Item;
use Up\Lib\FormatHelper\NumberFormatter;
use Up\Lib\FormatHelper\WordEndingResolver;

?>

<link rel="stylesheet" href="/css/catalog.css">
<div class="container">
	<div class="search_result_count">
		<?php if ($query === ''): ?>
		Видеокарты: найдено <?= $itemsAmount ?> товаров
		<?php else: ?>
		Результаты поиска по запросу "<?= $query ?>": найдено <?= $itemsAmount ?> товаров
		<?php endif; ?>
	</div>

	<div class="filters-item-list-row">
		<div class="filters-column">
			<form action="/" id="filter-form" method="get" class="filters card">
				<div class="filter-price">
					<div class="filter-title">Цена</div>
					<div class="price-range">
						<div class="price-box">
							<label for="min-price" class="price-label">мин. цена</label>
							<div class="price-input">
								₽<input type="number" id="min-price" name="min-price"  placeholder="<?= $price['minPrice']?>" class="input">
							</div>
						</div>
						<div class="range-dash"></div>
						<div class="price-box">
							<label for="max-price" class="price-label">макс. цена</label>
							<div class="price-input">
								₽<input type="number" id="max-price" name="max-price"  placeholder="<?= $price['maxPrice']?>" class="input">
							</div>
						</div>
					</div>
				</div>
				<div class="filter-specs">
					<?php foreach ($categories as $category) : ?>
					<div class="filter-category">
						<div class="filter-title"><?= htmlspecialchars($category->getName()) ?></div>
						<input type="checkbox" class="expand-category" id="<?=$category->getId()?>"/>
						<label class="filter-category-label" for=<?=$category->getId()?>>
							<span class="btn-back btn-expand"></span>
						</label>
						<div class="filter-specs-block">
							<?php foreach ($category->getSpecificationList()->getEntitiesArray() as $spec) : ?>
							<div class="spec-filter-section">
								<div><?= htmlspecialchars($spec->getName()) ?></div>
								<div class="spec-values-group">
									<?php foreach ($spec->getValue() as $value => $count) : ?>
									<label class="spec-value">
										<input type="checkbox" form="filter-form" class="category_spec_checkbox category_checkbox" name="<?= $spec->getId()?>" value="<?= $value ?>">
										<?= htmlspecialchars($value) ?> (<?=$count?>)
									</label>
									<?php endforeach;?>
								</div>
							</div>
							<?php endforeach;?>
						</div>
					</div>
					<?php endforeach;?>
				</div>
				<?php if($isAdmin): ?>
				<label class="deactivate_include_checkbox_label">
					<input type="checkbox" form="filter-form" class="deactivate_include_checkbox" name="deactivate_include">
					в том числе неактивные
				</label>
				<?php endif; ?>
				<div class="filter-tags">
					<div class="filter-title">Теги</div>
					<div class="tag-list">
						<?php foreach ($tags as $tag):?>
						<div class="tag">
							<input type="checkbox" class="category_tag_checkbox category_checkbox" name="tag" value="<?= $tag->getID() ?>" form="filter-form">
							<label><?= htmlspecialchars($tag->getName()) ?></label>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="filter-buttons">
					<div class="btn btn-normal filter-button redirect-button">Отфильтровать</div>
					<div class="btn btn-normal filter-button reset-button">Сбросить</div>
				</div>
			</form>
		</div>
		<div class="item-list">

			<?php if ($itemsAmount === 0): ?>
			<div class="message-no-results">
				По вашему запросу не найдено ни одного товара. Попробуйте изменить условия поиска.
			</div>
			<?php else: ?>
			<div class="item-sorting-box">
				<div class="item-sorting-box-title">Сортировка товаров:</div>
				<div class="item-sorting-button sorting-button <?= $sortingMethod === 'sort_order' ? 'active' : '' ?>" id="sort_order">
					По популярности
				</div>
				<div class="item-sorting-button sorting-button <?= $sortingMethod === 'price' ? 'active' : '' ?>" id="price">
					По цене <div class="sort-direction">↑</div>
				</div>
				<div class="item-sorting-button sorting-button <?= $sortingMethod === 'price_desc' ? 'active' : '' ?>" id="price_desc">
					По цене <div class="sort-direction">↓</div>
				</div>
				<div class="item-sorting-button sorting-button <?= $sortingMethod === 'name' ? 'active' : '' ?>" id="name">
					По названию<div class="sort-direction">↑</div>
				</div>
				<div class="item-sorting-button sorting-button <?= $sortingMethod === 'name_desc' ? 'active' : '' ?>" id="name_desc">
					По названию <div class="sort-direction">↓</div>
				</div>
			</div>
			<?php endif; ?>

			<?php
			foreach ($items as $item): ?>

				<?php if ($isAdmin):?>
				<form enctype="multipart/form-data" action="/admin/fastUpdateItem" name="fast-update" method="post" class="item card card-hover">
				<?php else:?>
				<div class="item card card-hover">
				<?php endif;?>
					<a href="<?= URLResolver::resolve('item-detail', ['id' => $item->getId()]) ?>">
						<picture>
							<source srcset="<?='/' . $item->getMainImage()->getPath('medium', 'webp') ?>" type="image/webp">
							<img class="item-image" src="<?= '/' . $item->getMainImage()->getPath('medium', 'jpeg') ?>" alt="Item Image">
						</picture>
					</a>
					<div class="item-other">
						<div class="item-other-to-top">
							<div class="item-other-header">
								<?php if ($isAdmin): ?>
								<input name="item-title" value="<?= htmlspecialchars($item->getTitle()) ?>" class="input">
								<?php else: ?>
								<a href="<?= URLResolver::resolve('item-detail', ['id' => $item->getId()]) ?>" class="item-title">
									<?= htmlspecialchars($item->getTitle()) ?>
								</a>
								<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
								<div class="btn-add-to-favorites" title="<?= $item->getId()?>">
									<svg class="add-to-favorites <?= $item->getIsFavorite() ? "favoriteActive" : ""?>">
										<use xlink:href="/img/sprites.svg#heart"></use>
									</svg>
								</div>
								<?php endif;?>
							</div>
							<?php if ($isAdmin): ?>
								<div class="textarea-container">
									<textarea name="item-short-description" class="item-short-description-textarea"><?=htmlspecialchars($item->getShortDescription())?></textarea>
								</div>
							<?php else: ?>
								<div class="item-short-description">
									<?=htmlspecialchars($item->getShortDescription())?>
								</div>
							<?php endif;?>
							<div class="item-other-footer">

								<div class="rating">
									<svg class="star-icon">
										<use xlink:href="./img/sprites.svg#star"></use>
									</svg>
									<div class="rating-value">
										<?= ($item->getAmountReviews() > 0) ? NumberFormatter::ratingFormat($item->getRating()) : '—' ?>
									</div>
									<div class="review-count">
										<?= ($item->getAmountReviews() > 0) ?
											"({$item->getAmountReviews()} "
											. WordEndingResolver::resolve($item->getAmountReviews(), array('отзыв','отзыва','отзывов'))
											. ')'
											: 'Отзывов пока нет.' ?>
									</div>
								</div>
								<?php if ($isAdmin): ?>
								<input name="item-sort_order" class="input display-order" type="number" value="<?= $item->getSortOrder() ?>">
								<div class="admin-btn-container">
									<a class="btn btn-normal" href="<?=URLResolver::resolve('edit-item', ['id' => $item->getId()])?>">Редактировать</a>
									<input type="submit" style="display: none">
									<?php if ($item->getIsActive()): ?>
									<a class="btn btn-deactivate">Скрыть</a>
									<?php else: ?>
									<a class="btn btn-return">Вернуть</a>
									<?php endif; ?>
								</div>
								<input name="item-price" class="input price" type="number" value="<?= htmlspecialchars($item->getPrice()) ?>">₽
								<input name="item-id" value="<?= $item->getId() ?>" type="hidden" class="input">
									<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
								<?php else: ?>
								<div class="price"><?= htmlspecialchars($item->getPrice()) ?> ₽</div>
								<?php endif;?>
							</div>
						</div>
						<?php if(!$item->getIsActive()): ?>
						<div class="no-active"></div>
						<?php endif; ?>
					</div>
				<?php if (!$isAdmin):?>
				</div>
				<?php else:?>
				</form>
				<?php endif;?>

			<?php endforeach; ?>

			<?= $paginator?>
		</div>
	</div>
</div>

<script src="/js/lib/fix-node.js"></script>
<script src="/js/fixed-filters.js"></script>

<!--<script src="/js/catalog-filters/filter-button.js"></script>-->
<script src="/js/catalog-filters/filter-reset.js"></script>
<script src="/js/catalog-filters/get-search-query.js"></script>
<script src="/js/catalog-filters/filter-get-query.js"></script>
<script src="/js/catalog-filters/queryPush.js"></script>
<script src="/js/catalog-filters/filter-set-query.js"></script>

<script src="/js/lib/showPopup.js"></script>
<script src="/js/lib/popup-disappear.js"></script>

<script src="/js/add-to-favorites.js"></script>

<?php if ($isAdmin): ?>
	<script src="/js/deactivate-item.js"></script>
	<script src="/js/fast-update-item.js"></script>
<?php endif;?>
