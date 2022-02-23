
let priceInputs = document.querySelectorAll('.price-input input');
let paramsQuery = (new URL(document.location)).searchParams;
let currentLocation= decodeURI(location.search.toString());
let searchField = document.getElementsByClassName('search-field');
let deactivateCheckbox = document.querySelector('.deactivate_include_checkbox');

if(deactivateCheckbox !== null)
{
	if(currentLocation.indexOf(deactivateCheckbox.name + '=' + deactivateCheckbox.value + '&') !== -1){
		deactivateCheckbox.checked = true;
	}
}

let params = new URLSearchParams(window.location.search);

for (let param of params)
{
	let checkboxName = param[0].slice(5,-1);
	let checkbox = document.querySelector(`[name="${checkboxName}"][value="${param[1]}"]`)
	if (checkbox !== null)
	{
		checkbox.checked = true;
	}
	if (param[0] === "tag[]")
	{
		let checkbox = document.querySelector(`[name='tag'][value="${param[1]}"]`)
		checkbox.checked = true;
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
