
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
		let checkboxPosition = checkbox.getBoundingClientRect();
		let left = checkboxPosition.left + 280;
		if (checkbox.classList.contains("category_tag_checkbox"))
		{
			left = 360
		}
		let top = checkboxPosition.top - 20;

		button.style.cssText = 'left:' + left + 'px; top: ' + top + 'px;' + 'display: block;';

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

