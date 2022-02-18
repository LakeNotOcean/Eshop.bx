let filterCheckboxes = document.getElementsByClassName('category_checkbox');
let priceInputs = document.getElementsByClassName('price-category-body-int');
let paramsQuery = (new URL(document.location)).searchParams;
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

for (let priceInput of priceInputs)
{
	if (currentLocation.indexOf('price') !== -1)
	{
		let price = paramsQuery.get('price')
		price = price.split('-')
		let minPrice = price[0];
		if (priceInput.classList.contains('price-category-body-int-min'))
		{

			priceInput.value = minPrice;
		}
		let maxPrice = price[1];
		if (priceInput.classList.contains('price-category-body-int-max'))
		{
			priceInput.value = maxPrice;
		}
	}
}