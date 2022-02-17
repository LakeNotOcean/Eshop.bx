let filterCheckboxes = document.getElementsByClassName('category_checkbox');
let currentLocation= decodeURI(location.search.toString())
for (let filterCheckbox of filterCheckboxes)
{
	let param = filterCheckbox.name;
	let value = filterCheckbox.value;
	let query = (param + '=' + value + '&');
	if (currentLocation.indexOf(query) !== -1)
	{
		filterCheckbox.checked = true
	}
}