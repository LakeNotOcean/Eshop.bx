<?php

/** @var int $currentPage */
/** @var int $pagesAmount */

?>

<div class="navigation">
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

<script src="/js/lib/paginator.js"></script>