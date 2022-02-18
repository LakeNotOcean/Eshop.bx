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
			<div class="filters card">
				<form id="filter-form" action="\"  method="get" >
				<div class="filter-category">
					<div class="price-category">
								<div class="filter-category-title filter-category-active">
									Цена
								</div>
						<div class="price-category-bodies">
							<div class="price-category-body">
								<div class="price-category-body-text">
									Мин. цена
								</div>
								<input type=text id="min-price" name="min-price" placeholder="<?=$price['minPrice']?>" class="price-category-body-int price-category-body-int-min">
							</div>
							<div class="price-category-body-center">
								-
							</div>
							<div class="price-category-body">
								<div class="price-category-body-text">
									Макс. цена
								</div>

									<input type=text id="max-price" name="maxp-rice" placeholder="<?=$price['maxPrice']?>" class="price-category-body-int price-category-body-int-max">

							</div>

						</div>
					</div>
					<div class="filter-category-specification">
						<?
						foreach ($categories as $category) : ?>
							<ul>
								<li>
									<a class="filter-category-active" href="#"><?=htmlspecialchars($category->getName())?></a>
									<input type="checkbox" class="filter-category-sub-specification" id=<?=$category->getId()?>  />
									<label class="filter-category-label" for=<?=$category->getId()?>  ></label>
									<ul style="display:none">
										<li>
											<div class = filter-category-specification-line></div>
												<? $specList = $category->getSpecificationList();
												$specList = $specList->getEntitiesArray();
												foreach ($specList as $spec) : ?>
													<div>
														<?= htmlspecialchars($spec->getName()) ?>
													</div>
													<?foreach ($spec->getValue() as $value=>$count) : ?>
														<div class="filter-category-specification-group">
															<div>
																<label>
																<input type="checkbox" form="filter-form" class="category_spec_checkbox category_checkbox" name="<?= $spec->getId() ?>" value="<?=$value?>">
																	<?=htmlspecialchars($value)?> </label>
															</div>
															<div class="filter-category-count">
																(<?=$count?>)
															</div>
														</div>
											<?php
											endforeach; ?>
												<?php
												endforeach; ?>
										</li>
									</ul>
								</li>
							</ul>

						<?php
						endforeach; ?>
					</div>
					<div class="tag-category">
						<div class="filter-category-title">
							Теги
						</div>
						<div class="tag-category-body">
							<?foreach ($tags as $tag) : ?>
								<div class="switch">
									<input type="checkbox" class="category_tag_checkbox category_checkbox" value="<?=$tag->getID()?>" form="filter-form">
									<label><?=$tag->getName()?></label>
								</div>
							<?php
							endforeach; ?>
						</div>



					</div>
					<input type="button" class="filter-button filter-button-checkbox redirect-button" id="button_on_checkbox"  style="display:none" value="Принять">
					<input type="button" class="filter-button redirect-button" value="Отфильтровать">
					<input type="button" class="filter-button reset-button" value="Сбросить">
				</form>
				</div>
			</div>
		</div>
		<div class="item-list">

			<?php if ($itemsAmount === 0): ?>
			<div class="item-no-results">
				По вашему запросу не найдено ни одного товара! Попробуйте изменить условия поиска
			</div>
			<?php endif; ?>

			<?php
			foreach ($items as $item) : ?>

				<<?= $isAdmin ? 'form enctype="multipart/form-data" action="' . URLResolver::resolve('fast-item-update') . '" method="post"' : "a href=\"" . URLResolver::resolve('item-detail', ['id' => $item->getId()]) . "\"" ?> class="item card card-hover">
					<picture>
						<source srcset="<?='/' . $item->getMainImage()->getPath('medium', 'webp') ?>" type="image/webp">
						<img class="item-image" src="<?= '/' . $item->getMainImage()->getPath('medium', 'jpeg') ?>" alt="Item Image">
					</picture>
					<div class="item-other">
						<div class="item-other-to-top">
							<div class="item-other-header">
								<?php if ($isAdmin): ?>
								<input name="item-title" value="<?= htmlspecialchars($item->getTitle()) ?>">
								<?php else: ?>
								<div class="item-title"><?= htmlspecialchars($item->getTitle()) ?></div>
								<?php endif;?>
								<svg class="add-to-favorites">
									<use xlink:href="/img/sprites.svg#heart"></use>
								</svg>
							</div>
							<?php if ($isAdmin): ?>
							<textarea name="item-short-description"><?=htmlspecialchars($item->getShortDescription())?></textarea>
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
							<input name="item-sort_order" class="display-order" type="number" value="<?= $item->getSortOrder() ?>">
							<div class="admin-btn-container">
								<a class="btn btn-normal" href="<?=URLResolver::resolve('edit-item-page', ['id' => $item->getId()])?>">Редактировать</a>
								<input type="submit" class="btn btn-normal" value="Сохранить">
								<a class="btn btn-delete">Удалить</a>
							</div>
							<input name="item-price" class="price" type="number" value="<?= htmlspecialchars($item->getPrice()) ?>">₽
							<input name="item-id" value="<?= $item->getId() ?>" type="hidden">
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

<script src="/js/filter-reset.js"></script>
<script src="/js/fix-node.js"></script>
<script src="/js/fixed-filters.js"></script>
<script src="/js/filter-button.js"></script>
<script src="/js/get-search-query.js"></script>
<script src="/js/filter-get-query.js"></script>
<script src="/js/queryPush.js"></script>
<script src="/js/filter-set-query.js"></script>



<?php if ($isAdmin): ?>
<script src="/js/delete-item.js"></script>
<?php endif;?>
