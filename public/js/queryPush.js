
let pageButtons = document.getElementsByClassName('redirect-button');
for (let pageButton of pageButtons)
{
	pageButton.addEventListener('click', (e) => {


		let searchParams = new URLSearchParams(location.search.toString())
		searchParams.set('page',pageButton.id)
		finalQuery = searchParams.toString()
		finalQuery = prepareQuery(finalQuery)
		localStorage.setItem('query',finalQuery)
		location = decodeURIComponent(finalQuery);
	});
}

	let filterButtons = document.getElementsByClassName('filter-button');
	for (let filterButton of filterButtons)
	{
		filterButton.addEventListener('click',(e) => {
			let filterQuery = getFilterQuery();
			let searchQuery = getSearchQuery();
			let pageQuery = "&page=1"
			let finalQuery = filterQuery + searchQuery + pageQuery;
			finalQuery = prepareQuery(finalQuery)
			localStorage.setItem('query', finalQuery);
			location = finalQuery;
		});

}

	let searchButton = document.querySelector(".search-field")
	searchButton.addEventListener("keydown",(e)=>{

		if (e.keyCode === 13)
		{

			let searchQuery = getSearchQuery();

			let pageQuery = "&page=1";
			let finalQuery = searchQuery + pageQuery;
			finalQuery = prepareQuery(finalQuery)

			localStorage.setItem('query',finalQuery)
			location = finalQuery;
		}
	})

function prepareQuery(finalQuery)
{

	if (finalQuery.slice(0,1)==='&')
	{
		finalQuery = finalQuery.slice(1);
	}
	if (finalQuery.slice(-1) === "&")
	{

		finalQuery = finalQuery.slice(0,-1)
	}
	if (finalQuery.length === 1)
	{
		finalQuery = "";
	}
	else
	{
		finalQuery = '?' + finalQuery;
	}
	return finalQuery;
}