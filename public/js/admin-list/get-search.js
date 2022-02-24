let searchButtonAdmin = document.querySelector(".container .search-icon");

searchButtonAdmin.addEventListener('click', (e)=>{
	let searchQuery = document.querySelector('.container .search-field');
	let search = searchQuery.value;
	let url =  new URL(window.location);
	url.searchParams.set('query',search);
	window.location = url;

})