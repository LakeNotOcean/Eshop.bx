
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
		let left = 360;
		let top = checkboxPosition.top - 20;

		button.style.cssText = 'left:' + left + 'px; top: ' + top + 'px;' + 'display: block;';

		document.addEventListener('scroll', () => {
			if (window.pageYOffset > 0)
			{
				top = checkboxPosition.top - 60
				button.style.cssText = 'left:' + left + 'px; top: ' + top + 'px;' + 'display: block;';
			}
			else
			{
				top = checkboxPosition.top - 20
				button.style.cssText = 'left:' + left + 'px; top: ' + top + 'px;' + 'display: block;';
			}

		});

	}
	else
	{
		button.style.display = "none";
	}
	}

