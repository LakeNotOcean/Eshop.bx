
let pageButtons = document.querySelectorAll('.redirect-button');
for (let pageButton of pageButtons)
{
	pageButton.addEventListener('click', () => {
		let searchParams = new URLSearchParams(window.location.search.toString())
		searchParams.set('page', pageButton.id)
		window.location = window.location.pathname + '?' + searchParams.toString();
	});
}
