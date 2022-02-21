<?php
/** @var array<UP\Entity\Item> $items */
/** @var array<Up\Entity\SpecificationCategory> $categories */
/** @var array<Up\Entity\ItemsTag> $tags */
/** @var array $price */
/** @var int $result_count */
/** @var int $currentPage */
/** @var int $itemsAmount */
/** @var int $pagesAmount */
/** @var string $query */
/** @var bool $isAdmin */
$pref = '_big';

use Up\Core\Router\URLResolver;
use Up\Entity\Item;

$pageHref = $isAdmin ? '/admin/' : '/';

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
								₽<input type=text id="min-price" name="min-price" placeholder="<?= $price['minPrice']?>" class="input">
							</div>
						</div>
						<div class="range-dash"></div>
						<div class="price-box">
							<label for="max-price" class="price-label">макс. цена</label>
							<div class="price-input">
								₽<input type=text id="max-price" name="max-price" placeholder="<?= $price['maxPrice']?>" class="input">
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
				<div class="filter-tags">
					<div class="filter-title">Теги</div>
					<div class="tag-list">
						<?php foreach ($tags as $tag):?>
						<div class="tag">
							<input type="checkbox" class="category_tag_checkbox category_checkbox" value="<?= $tag->getID() ?>" form="filter-form">
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
			<?php endif; ?>

			<?php
			foreach ($items as $item) : ?>

				<<?= $isAdmin ? 'form enctype="multipart/form-data" action="' . URLResolver::resolve('fast-item-update') . '" name="fast-update" method="post"' : "a href=\"" . URLResolver::resolve('item-detail', ['id' => $item->getId()]) . "\"" ?> class="item card card-hover">
					<picture>
						<source srcset="<?='/' . $item->getMainImage()->getPath('medium', 'webp') ?>" type="image/webp">
						<img class="item-image" src="<?= '/' . $item->getMainImage()->getPath('medium', 'jpeg') ?>" alt="Item Image">
					</picture>
					<div class="item-other">
						<div class="item-other-to-top">
							<div class="item-other-header">
								<?php if ($isAdmin): ?>
								<input name="item-title" value="<?= htmlspecialchars($item->getTitle()) ?>" class="input">
								<?php else: ?>
								<div class="item-title"><?= htmlspecialchars($item->getTitle()) ?></div>
								<?php endif;?>
								<svg class="add-to-favorites">
									<use xlink:href="/img/sprites.svg#heart"></use>
								</svg>
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
						</div>
						<div class="item-other-footer">
							<div class="rating">
								<svg class="star-icon">
									<use xlink:href="./img/sprites.svg#star"></use>
								</svg>
								<div class="rating-value"><?= (float)random_int(40, 50) / 10 ?></div>
								<div class="review-count">(<?= random_int(5, 50) ?> отзывов)</div>
							</div>
							<?php if ($isAdmin): ?>
							<input name="item-sort_order" class="input display-order" type="number" value="<?= $item->getSortOrder() ?>">
							<div class="admin-btn-container">
								<a class="btn btn-normal" href="<?=URLResolver::resolve('edit-item', ['id' => $item->getId()])?>">Редактировать</a>
								<input type="submit" style="display: none">
								<a class="btn btn-delete">Удалить</a>
							</div>
							<input name="item-price" class="input price" type="number" value="<?= htmlspecialchars($item->getPrice()) ?>">₽
							<input name="item-id" value="<?= $item->getId() ?>" type="hidden" class="input">
								<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
							<?php else: ?>
							<div class="price"><?= htmlspecialchars($item->getPrice()) ?> ₽</div>
							<?php endif;?>
						</div>
					</div>
				</<?= $isAdmin ? 'form' : 'a' ?>>

			<?php
			endforeach; ?>

			<div class="navigation">
				<!--				<div class="navigation-dots navigation-item">...</div>-->
				<?//= http_build_query(array_merge(['page' => $currentPage - 1], $_GET)) ?>
				<div id="<?=$currentPage - 1?>" class="navigation-page navigation-item redirect-button
				<?= $currentPage === 1 ? 'navigation-blocked' : '' ?>"> < </div>
				<div id="1" class="navigation-page navigation-item redirect-button
				<?= $currentPage === 1 ? 'navigation-active' : '' ?>">1</div>

				<?php if ($pagesAmount > 7 && $currentPage >= 1 + 4): ?>
					<div class="navigation-dots navigation-item">···</div>
				<?php endif;?>



				<?php $startPage = 2;
				$endPage = 5;
				if ($currentPage >= 5)
				{
					$startPage = $currentPage - 1;
					$endPage = $currentPage + 1;
				}
				if ($currentPage > $pagesAmount - 4)
				{
					$startPage = $pagesAmount - 4;
					$endPage = $pagesAmount - 1;
				}
				if ($pagesAmount <= 7)
				{
					$startPage = 2;
					$endPage = $pagesAmount - 1;
				}
				for ($i = $startPage; $i <= $endPage; $i++): ?>
					<div id="<?= $i ?>" class="navigation-page navigation-item redirect-button
					<?= $currentPage === $i ? 'navigation-active' : '' ?>"> <?= $i ?> </div>
				<?php endfor;?>

				<?php if ($pagesAmount > 7 && $currentPage <= $pagesAmount - 4): ?>
					<div class="navigation-dots navigation-item">···</div>
				<?php endif;?>
				<?php if ($pagesAmount > 1): ?>
				<div id="<?=$pagesAmount?>" class="navigation-page navigation-item redirect-button
				<?= $currentPage === $pagesAmount ? 'navigation-active' : '' ?>"><?= $pagesAmount?></div>
				<?php endif;?>

				<div id="<?=$currentPage + 1?>" class="navigation-page navigation-item redirect-button
				<?= $currentPage >= $pagesAmount ? 'navigation-blocked' : '' ?>"> > </div>
			</div>
		</div>
	</div>
</div>
<script src="/js/lib/popup.js"></script>
<script src="/js/lib/fix-node.js"></script>
<script src="/js/fixed-filters.js"></script>

<!--<script src="/js/catalog-filters/filter-button.js"></script>-->
<script src="/js/catalog-filters/filter-reset.js"></script>
<script src="/js/catalog-filters/get-search-query.js"></script>
<script src="/js/catalog-filters/filter-get-query.js"></script>
<script src="/js/catalog-filters/queryPush.js"></script>
<script src="/js/catalog-filters/filter-set-query.js"></script>
<script src="/js/popup-disappear.js"></script>
<?php if ($isAdmin): ?>
	<script src="/js/delete-item.js"></script>
	<script src="/js/fast-update-item.js"></script>
<?php endif;?>
