
function getFilterQuery(currentUrl)
{
	let filterCheckboxes = document.getElementsByClassName('category_spec_checkbox');
	for (let filterCheckbox of filterCheckboxes)
	{
		if (filterCheckbox.checked === true)
		{
			currentUrl.searchParams.append("spec[" + filterCheckbox.name + "][]",filterCheckbox.value)
		}
	}
	let tagCheckboxes = document.getElementsByClassName('category_tag_checkbox');
	for (let tagCheckbox of tagCheckboxes)
	{
		if (tagCheckbox.checked === true)
		{
			currentUrl.searchParams.append('tag[]',tagCheckbox.value)
		}
	}
	let deactivate = document.querySelector('.deactivate_include_checkbox');
	if(deactivate !== null && deactivate .checked)
		currentUrl.searchParams.set('deactivate_include','on')
	let minPriceInput = document.getElementById('min-price');
	let maxPriceInput = document.getElementById('max-price');
	if (minPriceInput.value !== '' && maxPriceInput.value !== '')
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
		currentUrl.searchParams.set('price',minPrice + "-" + maxPrice)
	}
}


