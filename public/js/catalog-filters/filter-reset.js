let filters = document.querySelector('.filters');
let resetButton = filters.querySelector('.reset-button');
resetButton.addEventListener('click', (e)=>{
	let checkboxInputs = filters.querySelectorAll('input[type=checkbox]')
	let textInputs = filters.querySelectorAll('input[type=text]')
	for (let checkboxInput of checkboxInputs)
	{
		checkboxInput.checked = false
	}
	for (let textInput of textInputs)
	{
		textInput.value = ''
	}
})
