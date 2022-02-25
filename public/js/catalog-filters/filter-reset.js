let filters = document.querySelector('.filters-column');
let resetButton = filters.querySelector('.reset-button');
resetButton.addEventListener('click', ()=>{
	let checkboxInputs = filters.querySelectorAll('input[type=checkbox]');
	let textInputs = filters.querySelectorAll('input[type=text]');
	let maxPriceInput = filters.querySelector('.max-price');
	let minPriceInput = filters.querySelector('.min-price');
	for (let checkboxInput of checkboxInputs)
	{
		checkboxInput.checked = false;
	}
	for (let textInput of textInputs)
	{
		textInput.value = '';
	}
	maxPriceInput.value = '';
	minPriceInput.value = '';
})
