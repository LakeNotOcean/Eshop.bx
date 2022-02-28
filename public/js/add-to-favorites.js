let addToFavoritesButtons = document.querySelectorAll('.btn-add-to-favorites');

addToFavoritesButtons.forEach(btnAddToFavorites => {
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
});

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
			addFavoriteAllIcons(itemId);
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
			removeFavoriteAllIcons(itemId);
			showPopup('Товар удалён из избранного');
		} else {
			showPopup('Товар не удалось удалить из избранного');
		}
	});
}

function addFavoriteAllIcons(itemId)
{
	addToFavoritesButtons.forEach(btnAddToFavorites => {
		let icon = btnAddToFavorites.querySelector('svg');
		if (itemId === btnAddToFavorites.title) {
			icon.classList.add('favoriteActive');
			icon.classList.remove('removedFromFavorites');
		}
	});
}

function removeFavoriteAllIcons(itemId)
{
	addToFavoritesButtons.forEach(btnAddToFavorites => {
		let icon = btnAddToFavorites.querySelector('svg');
		if (itemId === btnAddToFavorites.title) {
			icon.classList.remove('favoriteActive');
			icon.classList.add('removedFromFavorites');
		}
	});
}
