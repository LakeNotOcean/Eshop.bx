<?php
/** @var \Up\Entity\ItemDetail $item */
?>

<div class="container">
	<div class="text-warn">Вы уверены, что хотите удалить товар <?= "{$item->getTitle()} (id:{$item->getId()})" ?>?</div>
	<div class="text-warn">Внимание! Это может привести к удалению всех заказов с этим товаром!</div>
	<form action="<?= \Up\Core\Router\URLResolver::resolve('delete-item', ['id'=>$item->getId()]) ?>" method="post">
		<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
		<input type="submit" value="Подтвердить">
	</form>
</div>
