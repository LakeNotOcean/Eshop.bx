<?php
/** @var array<Item> $items */

/** @var int $result_count */
/** @var int $currentPage */
/** @var int $itemsAmount */
/** @var int $pagesAmount */

/** @var bool $isAdmin */
$pref = '_big';

use Up\Core\Router\URLResolver;
use Up\Entity\Item;

?>

<link rel="stylesheet" href="/css/catalog.css">
<div class="container">
	<div class="search_result_count">Видеокарты: найдено <?= $itemsAmount ?> штук</div>
	<div class="filters-item-list-row">
		<div class="filters-column">
			<div class="filters card">
				Фильтры
				<div class="filter-category">
					<div class="price-category"></div>
				</div>
			</div>
		</div>
		<div class="item-list">

			<?php
			foreach ($items as $item) : ?>

				<<?= $isAdmin ? 'form enctype="multipart/form-data" action="' . URLResolver::resolve('fast-item-update') . '" method="post"' : "a href=\"" . URLResolver::resolve('item-detail', ['id' => $item->getId()]) . "\"" ?> class="item card card-hover">
					<picture>
						<source srcset="../img/<?= $item->getId() . $pref ?>.webp" type="image/webp">
						<source srcset="../img/<?= $item->getId() . $pref ?>.png" type="image/png">
						<img class="item-image" src="../img/<?= $item->getId() ?>.png" alt="Item Image">
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
							<?php else: ?>
							<div class="price"><?= htmlspecialchars($item->getPrice()) ?> ₽</div>
							<?php endif;?>
						</div>
					</div>
				</<?= $isAdmin ? 'form' : 'a' ?>>

			<?php
			endforeach; ?>

			<div class="navigation">
				<a href="/?page=<?= $currentPage - 1 ?>" class="navigation-page navigation-item
				<?= $currentPage === 1 ? 'navigation-blocked' : '' ?>"> < </a>
				<a href="/?page=1" class="navigation-page navigation-item
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
					<a href="/?page=<?= $i ?>" class="navigation-page navigation-item
					<?= $currentPage === $i ? 'navigation-active' : '' ?>"> <?= $i ?> </a>
				<?php endfor;?>

				<?php if ($pagesAmount > 7 && $currentPage <= $pagesAmount - 4): ?>
					<div class="navigation-dots navigation-item">···</div>
				<?php endif;?>

				<a href="/?page=<?= $pagesAmount?>" class="navigation-page navigation-item
				<?= $currentPage === $pagesAmount ? 'navigation-active' : '' ?>"><?= $pagesAmount?></a>
				<a href="/?page=<?= $currentPage + 1 ?>" class="navigation-page navigation-item
				<?= $currentPage === $pagesAmount ? 'navigation-blocked' : '' ?>"> > </a>
			</div>
		</div>
	</div>
</div>

<script src="/js/fix-node.js"></script>
<script src="/js/fixed-filters.js"></script>
<?php if ($isAdmin): ?>
<script src="/js/delete-item.js"></script>
<?php endif;?>