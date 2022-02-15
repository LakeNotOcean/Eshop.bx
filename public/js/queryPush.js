
let pageButtons = document.getElementsByClassName('navigation-page');
for (let pageButton of pageButtons)
{
	pageButton.addEventListener('click',(e) => {
		let value = e.target.id;
		let url = new URL(window.location);
		url.searchParams.set("page", value);
		url = url.search;
		localStorage.setItem('query', url)
		location = url
	});
}


