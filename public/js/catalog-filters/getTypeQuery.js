function getTypeQuery(currentUrl)
{
	let url = new URLSearchParams(window.location.search);
	let param = url.get('type');
	currentUrl.searchParams.set("type",param)
}