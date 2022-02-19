
let checkboxes = document.getElementsByClassName('category_checkbox');

for (let checkbox of checkboxes)
{
	checkbox.addEventListener('click',() => showButton(checkbox));
}

function showButton(checkbox)
{
	let button = document.getElementById('button_on_checkbox');
	if (checkbox.checked)
	{
		button.style.display = "block";
		button.style.position = "static";
		checkbox.parentNode.insertBefore(button, checkbox);

		let scrollElement = document.querySelector(".filter-category")

		scrollElement.addEventListener('scroll', () => {
			button.style.display = "none";
		});
		document.addEventListener('scroll', () => {
			button.style.display = "none";
		});

	}
	else
	{
		button.style.display = "none";
	}
}
