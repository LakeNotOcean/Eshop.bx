let currentUrl = new URL(window.location.origin + window.location.pathname);

let sortingButtons = document.querySelectorAll('.sorting-button');
for (let sortingButton of sortingButtons)
{
	sortingButton.addEventListener('click', (e) => {
		getFilterQuery(currentUrl);
		getSearchQuery(currentUrl);
		currentUrl.searchParams.set('sorting', sortingButton.id);
		window.location = currentUrl;
	});
}

let filterButtons = document.querySelectorAll('.filter-button');
for (let filterButton of filterButtons)
{
	filterButton.addEventListener('click',() => {
		getTypeQuery(currentUrl)
		getFilterQuery(currentUrl);
		getSearchQuery(currentUrl);
		currentUrl.searchParams.set("page","1");
		window.location = currentUrl;
	});
}

let searchButton = document.querySelector('.search-field');
let searchButtonIcon = document.querySelector('.search-icon');

function searchListener(e) {
		if (currentUrl.pathname === "/catalog" || currentUrl.pathname == "/admin/" || currentUrl.pathname === "/catalog/" || currentUrl.pathname === "/catalog")
		{
			getSearchQuery(currentUrl);
			currentUrl.searchParams.set("page","1");
			window.location = currentUrl;
		}
		else
		{
			window.location = "/catalog" + "?" +  "type=0&query" + '=' +  e.target.value;
		}
}

searchButton.addEventListener("keydown", (e)=>{
	if (e.keyCode === 13)
	{
		searchListener(e)
	}
});
searchButtonIcon.addEventListener("click", searchListener);


