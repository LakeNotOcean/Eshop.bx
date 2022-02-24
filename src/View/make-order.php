<?php
/** @var array<Item> $items */
/** @var User $user */
/** @var int $orderSize */
/** @var int $cost */


use Up\Entity\Item;
use Up\Entity\User\User;
use Up\Entity\User\UserEnum;
use Up\Lib\WordProcessor\WordProcessor;

$firstName = "";
$secondName = "";
$phone = "";
$email = "";
if ($user->getRole()->getName() != UserEnum::Guest())
{
	$firstName = $user->getFirstName();
	$secondName = $user->getSecondName();
	$phone = $user->getPhone();
	$email = $user->getEmail();
}

?>

<link rel="stylesheet" href="/css/make-order.css">
<link rel="stylesheet" href="/css/lib/btn.css">
<div class="container">
	<div class="order-items">
		<?php
		foreach ($items as $item): ?>
			<div class="order-item">
				<a href="/item/<?= htmlspecialchars($item->getId()) ?>">
				<picture>
					<source srcset="<?='/' . $item->getMainImage()->getPath('medium', 'webp') ?>" type="image/webp">
					<img class="item-image" src="<?='/' . $item->getMainImage()->getPath('big', 'jpeg') ?>" alt="item-main-image">
				</picture>
				</a>

				<div class="item-info">
					<div class="item-title">
						<a href="/item/<?= htmlspecialchars($item->getId()) ?>"><?= htmlspecialchars($item->getTitle()) ?></a>
					</div>
					<div class="item-price"><?= $item->getPrice() ?> ₽</div>
				</div>
			</div>
		<?php
		endforeach; ?>
	</div>
	<?php if(!empty($items)): ?>
	<form action="<?= \Up\Core\Router\URLResolver::resolve('finish-order') ?>" method="post" enctype="multipart/form-data" class="user-data">
		<div class="user-data-title">Контактные данные</div>
		<div class="user-name">
			<label for="first-name" class="field">
				<input type="text" id="first-name" name="first-name" placeholder="Имя" required
					   value="<?= $firstName?>" class="input">
			</label>
			<label for="second-name" class="field">
				<input type="text" id="second-name" name="second-name" placeholder="Фамилия"
					   value="<?= $secondName?>" class="input">
			</label>
		</div>
		<div class="user-contact">
			<label for="phone" class="field">
				<input type="tel" pattern="\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}" id="phone" name="phone" placeholder="Телефон" required
					   value="<?= $phone?>" class="input">
			</label>
			<label for="email" class="field">
				<input type="email" id="email" name="email" placeholder="E-mail" required
					   value="<?= $email?>" class="input">
			</label>
		</div>
		<label for="comment" class="field">
			<textarea name="comment" id="comment" rows="10" class="order-comment" placeholder="Комментарий к заказу"></textarea>
		</label>
		<div class="order-summary">Итого: <?= $orderSize ?> <?= WordProcessor::formatWord($orderSize, 'товар') ?> на сумму <?= $cost ?> ₽</div>
		<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
		<input type="submit" value="Подтвердить" class="btn btn-normal input">
	</form>
	<?php else: ?>
	<div class="cart-notification">Ваша корзина пуста! Добавьте товары в корзину, чтобы оформить заказ</div>
	<a href="/" class="btn btn-normal">Вернуться на главную</a>
	<?php endif; ?>
</div>

<script src="/js/lib/phone-input.js"></script>
