<?php

use Up\Core\Router\URLResolver;

?>
<form action="<?= URLResolver::resolve('form-post') ?>" method="post" enctype="multipart/form-data">
	<input type="file" name="testName">
	<input type="number" name="itemId" placeholder="id item">
	<button type="submit">отправить</button>
</form>