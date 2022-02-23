

let sortingButtons = document.getElementsByClassName('sorting-button');
for (let sortingButton of sortingButtons)
{
	sortingButton.addEventListener('click', (e) => {
		let searchParams = new URLSearchParams(location.search.toString())
		searchParams.set('sorting', sortingButton.id)
		let finalQuery = searchParams.toString()
		finalQuery = prepareQuery(finalQuery)
		localStorage.setItem('query',finalQuery)
		window.location = decodeURIComponent(finalQuery);
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
		window.location = finalQuery;
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
		window.location = finalQuery;
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
