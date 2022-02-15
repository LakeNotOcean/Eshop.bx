
let pageButtons = document.getElementsByClassName('navigation-page');
for (let pageButton of pageButtons)
{
	pageButton.addEventListener('click',(e) => {
		let value = e.target.id;
		alert(value)
		let url = new URL(window.location);
		url.searchParams.set("page", value);
		url = url.search;
		localStorage.setItem('query', url)

		alert(url.toString())
		location = url
	});
}


