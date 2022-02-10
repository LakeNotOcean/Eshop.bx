<?php
/** @var array<Entity\Item> $items */

/** @var int $result_count */
$pref = '_big';
use Up\Core\Router\URLResolver;

?>

<link rel="stylesheet" href="./css/catalog.css">
<div class="container">
	<div class="search_result_count">Видеокарты: найдено <?= 5 ?> штук</div>
	<div class="filters-item-list-row">
		<div class="filters-column">
			<div class="filters">
				Фильтры
				<div class="filter-category">
					<div class="price-category"></div>
				</div>
			</div>
		</div>
		<div class="item-list">

			<?php
			foreach ($items as $item) : ?>

				<a class="item" href="<?= URLResolver::resolve('item-detail', ['id' => $item->getId()]) ?>">
					<picture>
						<source srcset="../img/<?= $item->getId() . $pref  ?>.webp" type="image/webp">
						<source srcset="../img/<?= $item->getId()  . $pref  ?>.png" type="image/png">
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
				<a href="/?page=1" class="navigation-page navigation-item"> < </a>
				<a href="/?page=1" class="navigation-page navigation-item navigation-active"> 1 </a>
				<a href="/?page=2" class="navigation-page navigation-item">2</a>
				<a href="/?page=3" class="navigation-page navigation-item">3</a>
				<a href="/?page=4" class="navigation-page navigation-item">4</a>
				<div class="navigation-dots navigation-item">...</div>
				<a href="/?page=15" class="navigation-page navigation-item">15</a>
				<a href="/?page=2" class="navigation-page navigation-item">></a>
			</div>
		</div>
	</div>
</div>
<script src="./js/fixed-filters.js"></script>
