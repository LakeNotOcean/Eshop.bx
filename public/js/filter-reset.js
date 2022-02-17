let resetButton = document.querySelector('.reset-button');
resetButton.addEventListener('click', (e)=>{
	let filterCheckboxes = document.querySelectorAll('.category_checkbox')
	for (let filterCheckbox of filterCheckboxes)
	{
		filterCheckbox.checked = false
	}
})