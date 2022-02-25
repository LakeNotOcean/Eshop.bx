
function getSearchQuery(currentUrl)
{
	let searchInput = document.querySelector(".search-field");
	let searchQuery = searchInput.value;
	currentUrl.searchParams.set(searchInput.id,searchQuery);
}
