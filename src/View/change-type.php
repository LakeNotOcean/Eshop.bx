<?php

/** @var array $types */
/** @var string $paginator */

?>

<link rel="stylesheet" href="/css/change-type.css">
<div class="container">

		<?php
		foreach ($types as $type):?>
			<div class="types-card card-outline" id=<?=$type->getId()?>>
				<?=$type->getName()?>
			</div>
		<?php endforeach;?>
	<?=$paginator?>
</div>

<script src="/js/change-type/change-type.js"></script>

