let filterCheckboxes = document.getElementsByClassName('category_checkbox');
let priceInputs = document.querySelectorAll('.price-input input');
let paramsQuery = (new URL(document.location)).searchParams;
let currentLocation= decodeURI(location.search.toString());
let searchField = document.getElementsByClassName('search-field');

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

for (let priceInput of priceInputs)
{
	if (currentLocation.indexOf('price') !== -1)
	{
		let price = paramsQuery.get('price')
		price = price.split('-')
		let minPrice = price[0];
		if (priceInput.id === "min-price")
		{
			priceInput.value = minPrice;
		}
		let maxPrice = price[1];
		if (priceInput.id === "max-price")
		{
			priceInput.value = maxPrice;
		}
	}
}

if (currentLocation.indexOf('query') !== -1)
{
	searchField.value =  paramsQuery.get('query');
}
