<?php

use Up\Core\Message\Request;

/** @var $exception Exception */
/** @var $request Request */
?>

<div class="exception">
	<?= $exception->getTraceAsString() ?>
</div>
<br>
<div class="request">
	<?= var_export($request) ?>
</div>
