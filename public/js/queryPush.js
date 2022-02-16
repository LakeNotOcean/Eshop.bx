
let pageButtons = document.getElementsByClassName('navigation-page');
for (let pageButton of pageButtons)
{
	pageButton.addEventListener('click', (e) => {
		let value = e.target.id;
		let url = new URL(window.location);
		url.searchParams.set("page", value);
		url = url.search;
		localStorage.setItem('fullQuery', url)
		location = url
	});
}

	let filterButtons = document.getElementsByClassName('filter-button');
	for (let filterButton of filterButtons)
	{

		filterButton.addEventListener('click',(e) => {
			let url = new URL(window.location);
			url.searchParams.set("page", "1");
			url.searchParams.get("query");
			url = url.search;
			localStorage.setItem('fullQuery', url);
			location = url;
		});

}


