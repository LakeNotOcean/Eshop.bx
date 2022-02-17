
function getFilterQuery()
{

	let filterCheckboxes = document.getElementsByClassName('category_checkbox');
	let finalQuery = '';
	for (let filterCheckbox of filterCheckboxes)
	{
		if (filterCheckbox.checked === true)
		{
			let param = filterCheckbox.name;
			let value = filterCheckbox.value;
			finalQuery += param + '=' + value + '&'
		}
	}

	let minPriceInput = document.getElementsByClassName('min-price');
	let maxPriceInput = document.getElementsByClassName('min-price');
	if (minPriceInput.value!==undefined || maxPriceInput.value!==undefined)
	{
		let minPrice = 0;
		let maxPrice = 0;
		if (minPriceInput.value===undefined)
		{
			 minPrice= minPriceInput.placeholder;
		}
		else
		{
			minPrice = minPriceInput.value()
		}
		if (maxPriceInput.value===undefined)
		{
			maxPrice = maxPriceInput.placeholder;
		}
		else
		{
			maxPrice = maxPriceInput.value()
		}
		finalQuery += "price" + "=" + minPrice + "-" + maxPrice + "&"
	}
	return finalQuery;
}


