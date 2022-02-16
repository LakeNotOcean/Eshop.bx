let phoneInputs = document.querySelectorAll('input[type=tel]');

for (let phoneInput of phoneInputs) {
	phoneInput.addEventListener('invalid', () => {
		phoneInput.setCustomValidity('Телефон введён не полностью');
	});
	phoneInput.addEventListener('input', (e) => {
		phoneInput.setCustomValidity('');
		let character = e.data;
		if (character !== null)
		{
			phoneInput.value = validatePhone(phoneInput.value, character);
		}
	});
}

//"+7 (___) ___-__-__"
function validatePhone(value, key)
{
	let groups = [null, '+', '7', ' ', '(', '', ')', ' ', '', '-', '', '-', ''];
	let phoneRegex = /(\+)?(7)?( )?(\()?(\d{0,3})?(\))?( )?(\d{0,3})?(-)?(\d{0,2})?(-)?(\d{0,2})?/;
	let matches = phoneRegex.exec(value);
	let phone = '';
	for (let i = 1; i <= 12; i++)
	{
		if (matches[i] === undefined)
		{
			if (phone !== value)
			{
				for (let j = i; j <= 12 && groups[j] !== '' && groups[j] !== key; j++)
				{
					phone += groups[j];
				}
			}
			if (phone !== value)
			{
				return phoneRegex.exec(phone + key)[0];
			}
			return phone;
		}
		phone += matches[i];
	}
	return phone;
}
