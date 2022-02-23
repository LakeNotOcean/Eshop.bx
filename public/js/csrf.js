export function getCSRFFromForm(form)
{
	let csrfInput = form.querySelector('.token');
	return {
		'name': csrfInput.getAttribute('name'),
		'value': csrfInput.getAttribute('value'),
		'input': csrfInput
	};
}