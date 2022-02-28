<?php

/** @var array $exceptions */
/** @var array $request */

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

	<h1 class="request-title">Request</h1>

	<div class="method">
		<?php
		foreach ($request as $methodName => $method):
		if (empty($method))
		{
			continue;
		}
		?>
			<div class="method-name"><?= $methodName?></div>
			<?php foreach ($method as $key => $value):
			if (is_object($value) || is_array($value))
			{
				echo "\"$key\" => ";
				var_dump($value);
			}
			else
			{?>
				<div class="method-parameter">
					<?= "\"$key\" => $value"?>
				</div>
			<?php
			}
			endforeach;?>
		<?php endforeach;?>
	</div>
</div>
