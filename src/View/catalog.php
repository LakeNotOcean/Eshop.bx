<?php
/** @var array<UP\Entity\Item> $items */
/** @var array<Up\Entity\SpecificationCategory> $categories */
/** @var array<Up\Entity\ItemsTag> $tags */
/** @var int $result_count */
/** @var int $currentPage */
/** @var int $itemsAmount */
/** @var int $pagesAmount */

$pref = '_big';

use Up\Core\Router\URLResolver;
?>

<link rel="stylesheet" href="./css/catalog.css">
<div class="container">
	<div class="search_result_count">Видеокарты: найдено <?= $itemsAmount ?> штук</div>
	<div class="filters-item-list-row">
		<div class="filters-column">
			<div class="filters">
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
								<input type=text id="minprice" name="minprice" value="100" class="price-category-body-int">
							</div>
							<div class="price-category-body-center">
								-
							</div>
							<div class="price-category-body">
								<div class="price-category-body-text">
									Макс. цена
								</div>

									<input type=text id="maxprice" name="maxprice" value="1000" class="price-category-body-int">

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
																<input type="checkbox" form="filter-form" class="category_checkbox" name="<?= $spec->getId() ?>[]" value="<?=$value?>">
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
									<input type="checkbox" class="category_checkbox" name="tag[]" value="<?=$tag->getID()?>" form="filter-form">
									<label><?=$tag->getName()?></label>
								</div>
							<?php
							endforeach; ?>
						</div>



					</div>
					<input type="submit" class="filter-button filter-button-checkbox" id="button_on_checkbox" form="filter-form" style="display:none" value="Принять">
					<input type="submit" class="filter-button" form="filter-form" value="Отфильтровать">
				</form>
				</div>
			</div>
		</div>
		<div class="item-list">

			<?php
			foreach ($items as $item) : ?>

				<a class="item" href="<?= URLResolver::resolve('item-detail', ['id' => $item->getId()]) ?>">
					<picture>
						<source srcset="../img/<?= $item->getId() . $pref ?>.webp" type="image/webp">
						<source srcset="../img/<?= $item->getId() . $pref ?>.png" type="image/png">
						<img class="item-image" src="../img/<?= $item->getId() ?>.png" alt="Item Image">
					</picture>
					<div class="item-other">
						<div class="item-other-to-top">
							<div class="item-other-header">
								<div class="item-title"><?= htmlspecialchars($item->getTitle()) ?></div>
								<svg class="add-to-favorites">
									<use xlink:href="/img/sprites.svg#heart"></use>
								</svg>
							</div>
							<div class="item-short-description">
								<?= htmlspecialchars($item->getShortDescription()) ?>
							</div>
						</div>
						<div class="item-other-footer">
							<div class="rating">
								<svg class="star-icon">
									<use xlink:href="./img/sprites.svg#star"></use>
								</svg>
								<div class="rating-value"><?= (float)random_int(40, 50) / 10 ?></div>
								<div class="review-count">(<?= random_int(5, 50) ?> отзывов)</div>
							</div>
							<div class="price"><?= htmlspecialchars($item->getPrice()) ?> ₽</div>
						</div>
					</div>
				</a>

			<?php
			endforeach; ?>

			<div class="navigation">
				<!--				<div class="navigation-dots navigation-item">...</div>-->
				<div id="1" class="navigation-page navigation-item"> << </div>
				<?php
				($currentPage > 3) ? $startPage = $currentPage - 3 : $startPage = 1;
				($currentPage <= $pagesAmount - 3) ? $endPage = $currentPage + 3 : $endPage = $pagesAmount;
				for ($i = $startPage; $i <= $endPage; $i++):
					if ($currentPage === $i)
					{
						$activeClass = 'navigation-active';
					}
					else
					{
						$activeClass = '';
					}
					?>
					<div id="<?= $i ?>" class="navigation-page navigation-item <?= $activeClass ?>"><?= $i ?></div>

				<?php
				endfor; ?>

				<div id="<?=$pagesAmount?>" class="navigation-page navigation-item"> >> </div>
			</div>
		</div>
	</div>
</div>
<script src="/js/fix-node.js"></script>
<script src="/js/fixed-filters.js"></script>
<script src="/js/filter-button.js"></script>
<script src="/js/queryPush.js"></script>
