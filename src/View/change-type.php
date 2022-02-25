<?php

/** @var array $typesWithItems */
/** @var string $paginator */

?>

<link rel="stylesheet" href="/css/change-type.css">
<div class="container">

		<?php
		foreach ($typesWithItems as $typesWithItem):
			$item = $typesWithItem['item'];
			$type = $typesWithItem['type']?>
			<div class="types-card card-outline card-hover" id=<?= $type->getId()?>>
				<div>
					<picture>
						<source srcset="<?='/' . $item->getMainImage()->getPath('medium', 'webp') ?>" type="image/webp">
						<img src=" <?='/'.$item->getMainImage()->getPath('medium', 'jpeg')?>" alt="item-main-image" class="main-image">
					</picture>
				</div>
				<div class="type-name">
					<?= htmlspecialchars($type->getName())?>
				</div>

			</div>
		<?php endforeach;?>
	<?=$paginator?>
</div>

<script src="/js/change-type/change-type.js"></script>
