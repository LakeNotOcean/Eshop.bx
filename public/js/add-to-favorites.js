let addToFavoritesButtons = document.querySelectorAll('.btn-add-to-favorites');

for (let addToFavorites of addToFavoritesButtons)
{
	addToFavorites.addEventListener('click', () => {
		console.log(addToFavorites.title);
	})
}
