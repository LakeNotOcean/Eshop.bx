export function checkInputs(inputFields)
{
	let isSuccess = true;
	inputFields.forEach(field => {
		const value = field.doc.value;
		const args = field.args;
		const result = stringFormat(value, args);
		const errorSpan = document.querySelector(`[data-source="${field.doc.id}"]`);
		if (result === true)
		{
			field.doc.classList.remove('error');
			field.doc.classList.add('success');
			errorSpan.classList.remove('visible');
		}
		else
		{
			isSuccess = false;
			field.doc.classList.add('error');
			field.doc.classList.remove('success');
			errorSpan.classList.add('visible');
		}
	});
	return isSuccess;
}

export function makePostQuery(inputFields, urlPost, successMess = '', unsuccessfulMess = '')
{
	let formData = new URLSearchParams();
	inputFields.forEach(field => {
		formData.append(field.name, field.doc.value);
	});
	console.log('post is work');
	return fetch(urlPost, {
		method: 'post',
		body: formData,
	}).then((response) => {
		if (response.ok)
		{
			resultMessage(successMess);
			window.location.replace('/');
		}
		else
		{
			inputFields.forEach(field => {
				field.doc.classList.add('error');
				field.doc.classList.remove('success');
			});
			resultMessage(unsuccessfulMess, false);

		}
	});
}

function resultMessage(resultString = '', isSuccess = true)
{
	let result = document.createElement('div');
	result.textContent = resultString;
	result.classList.add('result-message');
	if (!isSuccess)
	{
		result.style.background = 'red';
	}
	document.querySelector('.container').append(result);
	setTimeout(() => {
		result.remove();
	}, 5000);
}

function stringFormat(value, args)
{
	let template = args.template || '';
	let minLength = args.minLength || 1;
	let maxLength = args.maxLength || 20;
	let regex = new RegExp(template);
	let match = value.match(regex) || [];
	return match[0] === value && value.length >= minLength && value.length <= maxLength;

}