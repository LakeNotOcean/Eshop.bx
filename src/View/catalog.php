<?php
/** @var array<Entity\Item> $items */

/** @var int $result_count */
/** @var int $currentPage */
/** @var int $itemsAmount */
/** @var int $pagesAmount */
/** @var string $query */

/** @var bool $isAdmin */
$pref = '_big';

use Up\Core\Router\URLResolver;

$isAdmin = true;

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
			<div class="filters">
				Фильтры
				<div class="filter-category">
					<div class="price-category"></div>
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
			foreach ($items as $item):
				$imageUrl = URLResolver::resolve('item-detail', ['id' => $item->getId()]);
			?>

				<div class="item">
					<a href="<?= $imageUrl?>">
						<picture>
							<source srcset="../img/<?= $item->getId() . $pref ?>.webp" type="image/webp">
							<source srcset="../img/<?= $item->getId() . $pref ?>.png" type="image/png">
							<img class="item-image" src="../img/<?= $item->getId() ?>.png" alt="Item Image">
						</picture>
					</a>
					<div class="item-other">
						<div class="item-other-to-top">
							<div class="item-other-header">
								<a class="item-title" href="<?= $imageUrl ?>"><?= htmlspecialchars($item->getTitle()) ?></a>
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
							<?php if ($isAdmin): ?>
							<div class="admin-btn-container">
								<a class="btn btn-normal" href="">Изменить</a>
								<a class="btn btn-delete" href="">Удалить</a>
							</div>
							<?php endif;?>
							<div class="price"><?= htmlspecialchars($item->getPrice()) ?> ₽</div>
						</div>
					</div>
				</div>

			<?php
			endforeach; ?>

			<div class="navigation">
				<?//= http_build_query(array_merge(['page' => $currentPage - 1], $_GET)) ?>
				<a href="/?<?= http_build_query(array_merge($_GET, ['page' => ($currentPage - 1)])) ?>" class="navigation-page navigation-item
				<?= $currentPage === 1 ? 'navigation-blocked' : '' ?>"> < </a>
				<a href="/?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>" class="navigation-page navigation-item
				<?= $currentPage === 1 ? 'navigation-active' : '' ?>">1</a>

				<?php if ($pagesAmount > 7 && $currentPage >= 1 + 4): ?>
					<div class="navigation-dots navigation-item">···</div>
				<?php endif;?>

				<?php
				$startPage = 2;
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
					<a href="/?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" class="navigation-page navigation-item
					<?= $currentPage === $i ? 'navigation-active' : '' ?>"> <?= $i ?> </a>
				<?php endfor;?>

				<?php if ($pagesAmount > 7 && $currentPage <= $pagesAmount - 4): ?>
					<div class="navigation-dots navigation-item">···</div>
				<?php endif;?>

				<?php if ($pagesAmount > 1): ?>
				<a href="/?<?= http_build_query(array_merge($_GET, ['page' => $pagesAmount])) ?>" class="navigation-page navigation-item
				<?= $currentPage === $pagesAmount ? 'navigation-active' : '' ?>"><?= $pagesAmount?></a>
				<?php endif;?>

				<a href="/?<?= http_build_query(array_merge($_GET, ['page' => ($currentPage + 1)])) ?>" class="navigation-page navigation-item
				<?= $currentPage >= $pagesAmount ? 'navigation-blocked' : '' ?>"> > </a>
			</div>
		</div>
	</div>
</div>

<script src="/js/fix-node.js"></script>
<script src="/js/fixed-filters.js"></script>
