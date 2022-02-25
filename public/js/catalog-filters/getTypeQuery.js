function getTypeQuery(currentUrl)
{
	let url = new URLSearchParams(window.location.search);
	let param = url.get('type');
	if (param === null)
	{
		param = 0;
	}
	currentUrl.searchParams.set("type",param);
}