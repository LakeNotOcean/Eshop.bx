let addToFavoritesButtons = document.querySelectorAll('.btn-add-to-favorites');

for (let btnAddToFavorites of addToFavoritesButtons)
{
	let icon = btnAddToFavorites.querySelector('svg');
	let itemId = btnAddToFavorites.title;

	btnAddToFavorites.addEventListener('click', () => {
		if (isFavorite(icon)) {
			removeFromFavorites(itemId, icon);
		} else {
			addToFavorites(itemId, icon);
		}
	})

	btnAddToFavorites.addEventListener('mouseleave', () => {
		icon.classList.remove('removedFromFavorites');
	})
}

function isFavorite(icon) {
	return icon.classList.contains('favoriteActive')
}

function addToFavorites(itemId, icon)
{
	let postBody = new FormData();
	postBody.append('favorite-item-id', itemId);
	let token = document.querySelector('.token');
	postBody.append(token.name, token.value);

	fetch('/addToFavorites', {
		method: 'post',
		body: postBody
	}).then((r) => {
		if (r.ok) {
			showPopup('Заказ удалён');
			icon.classList.add('favoriteActive');
			icon.classList.remove('removedFromFavorites');
			showPopup('Товар добавлен в избранное');
		} else {
			showPopup('Войдите, чтобы сохранять понравившиеся товары');
		}
	});
}

function removeFromFavorites(itemId, icon)
{
	let postBody = new FormData();
	postBody.append('favorite-item-id', itemId);
	let token = document.querySelector('.token');
	postBody.append(token.name, token.value);

	fetch('/removeFromFavorites', {
		method: 'post',
		body: postBody
	}).then((r) => {
		if (r.ok) {
			showPopup('Заказ удалён');
			icon.classList.remove('favoriteActive');
			icon.classList.add('removedFromFavorites');
			showPopup('Товар удалён из избранного');
		} else {
			showPopup('Товар не удалось удалить из избранного');
		}
	});
}
