
function getSearchQuery()
{
	let searchInput = document.querySelector(".search-field")
	let searchQuery = searchInput.value

	if (searchQuery!== '')
	{
		searchQuery =  "&" + searchInput.id + "=" + searchQuery;
		return searchQuery;
	}
	return "";
}
