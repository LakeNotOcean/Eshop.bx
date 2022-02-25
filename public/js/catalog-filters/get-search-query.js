
function getSearchQuery(currentUrl)
{
	let searchInput = document.querySelector(".search-field");
	let searchQuery = searchInput.value;

	if (searchQuery!== '')
	{
		currentUrl.searchParams.set(searchInput.id,searchQuery);
	}
}
