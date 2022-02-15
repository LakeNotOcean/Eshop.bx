<?php

use Up\Core\Message\Request;

/** @var array $exceptions */
/** @var Request $request */

?>

<link rel="stylesheet" href="/css/debug.css">

<div class="container">
	<h1 class="traceback-title">Traceback</h1>

	<?php foreach ($exceptions as $exception):?>
		<div class="exception">
			<div class="exception-info">
				<?= $exception['info']?>
			</div>
			<div class="exception-code-line">
				<?= $exception['codeLine']?>
			</div>
		</div>
	<?php endforeach;?>

	<h1 class="traceback-title">Request</h1>

	<div class="request">
		<?= var_export($request) ?>
	</div>
</div>
