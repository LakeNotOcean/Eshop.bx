let resetButton = document.querySelector('.reset-button');
resetButton.addEventListener('click', (e)=>{
	let filterCheckboxes = document.querySelectorAll('.category_checkbox')
	let priceInputs = document.getElementsByClassName('price-category-body-int');
	for (let filterCheckbox of filterCheckboxes)
	{
		filterCheckbox.checked = false
	}
	for (let priceInput of priceInputs)
	{
		priceInput.value = ''
	}
})
