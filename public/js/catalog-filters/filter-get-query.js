
function getFilterQuery()
{
	let filterCheckboxes = document.getElementsByClassName('category_spec_checkbox');
	let finalQuery = '';
	for (let filterCheckbox of filterCheckboxes)
	{
		if (filterCheckbox.checked === true)
		{
			let param = filterCheckbox.name;
			let value = filterCheckbox.value;
			finalQuery += 'spec[]=' + param + '=' + value + '&'
		}
	}
	let tagCheckboxes = document.getElementsByClassName('category_tag_checkbox');
	for (let tagCheckbox of tagCheckboxes)
	{
		if (tagCheckbox.checked === true)
		{
			let value = tagCheckbox.value;
			finalQuery += 'tag[]=' + value + '&'
		}
	}
	let deactivate = document.querySelector('.deactivate_include_checkbox');
	if(deactivate !== null && deactivate .checked)
		finalQuery += 'deactivate_include=on&';
	let minPriceInput = document.getElementById('min-price');
	let maxPriceInput = document.getElementById('max-price');
	if (minPriceInput.value==='' && maxPriceInput.value==='')
	{
		return finalQuery;
	}
	else
	{
		let minPrice;
		let maxPrice;
		if (minPriceInput.value==='')
		{
			minPrice = minPriceInput.placeholder;
		}
		else
		{
			minPrice = minPriceInput.value
		}
		if (maxPriceInput.value==='')
		{
			maxPrice = maxPriceInput.placeholder;
		}
		else
		{
			maxPrice = maxPriceInput.value
		}
		finalQuery += "price" + "=" + minPrice + "-" + maxPrice + "&"
	}
	return finalQuery;
}


