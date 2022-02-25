
let currentUrl = new URL(window.location);
let sortingButtons = document.querySelectorAll('.sorting-button');
for (let sortingButton of sortingButtons)
{
	sortingButton.addEventListener('click', (e) => {
		currentUrl.searchParams.set('sorting', sortingButton.id);
		window.location = currentUrl;
	});
}

let filterButtons = document.querySelectorAll('.filter-button');
for (let filterButton of filterButtons)
{
	filterButton.addEventListener('click',() => {
		getFilterQuery(currentUrl);
		getSearchQuery(currentUrl);
		currentUrl.searchParams.append("page","1");
		window.location = currentUrl;
	});
}

let searchButton = document.querySelector('.search-field');
let searchButtonIcon = document.querySelector('.search-icon');

function searchListener(e) {
		getSearchQuery(currentUrl);
		currentUrl.searchParams.append("page","1");
		window.location = currentUrl;
}

searchButton.addEventListener("keydown", (e)=>{
	if (e.keyCode === 13)
	{
		searchListener(e)
	}
});
searchButtonIcon.addEventListener("click", searchListener);


