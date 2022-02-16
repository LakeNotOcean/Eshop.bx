const passwordValue = document.getElementById('password');
const repeatPasswordValue = document.getElementById('repeatPassword');

const values = [
	{
		value: document.getElementById('login'),
		args: { template: '^[a-zA-Z0-9]+$', minLength: 3, maxLength: 20 }, name: 'login',
	},
	{
		value: document.getElementById('firstName'),
		args: { template: '^(?:[a-zA-z]+|[а-яА-Яё]+)$', minLength: 1, maxLength: 30 }, name: 'firstName',
	},
	{
		value: document.getElementById('secondName'),
		args: { template: '^(?:[a-zA-z]+|[а-яА-Яё]+)$', minLength: 1, maxLength: 30 }, name: 'secondName',
	},
	{
		value: passwordValue,
		args: { template: '^[a-zA-Z0-9]+$', minLength: 5, maxLength: 50 }, name: 'password',
	},
	{
		value: document.getElementById('email'),
		args: {
			template: '^(([^<>()[\\]\\.,;:\\s@\\"]+(\\.[^<>()[\\]\\.,;:\\s@\\"]+)*)|(\\".+\\"))@(([^<>()[\\]\\.,;:\\s@\\"]+\\.)+[^<>()[\\]\\.,;:\\s@\\"]{2,})$',
			minLength: 5,
			maxLength: 50,
		}, name: 'email',
	},
	{
		value: document.getElementById('phone'),
		args: { template: '^(\\+7|7|8)?[\\s\\-]?\\(?[489][0-9]{2}\\)?[\\s\\-]?[0-9]{3}[\\s\\-]?[0-9]{2}[\\s\\-]?[0-9]{2}$' },
		name: 'phone',
	},
];

function checkInputs()
{
	let isSuccess = true;
	values.forEach(field => {
		const value = field.value.value;
		const args = field.args;
		const result = stringFormat(value, args);
		const errorSpan = document.querySelector(`[data-source="${field.value.id}"]`);
		if (result === true)
		{
			field.value.classList.remove('error');
			field.value.classList.add('success');
			errorSpan.classList.remove('visible');
		}
		else
		{
			isSuccess = false;
			field.value.classList.add('error');
			field.value.classList.remove('success');
			errorSpan.classList.add('visible');
		}
	});
	const errorSpan = document.querySelector(`[data-source="repeatPassword"]`);
	if (!repeatPassword(passwordValue.value, repeatPasswordValue.value))
	{
		isSuccess = false;
		repeatPasswordValue.classList.add('error');
		errorSpan.classList.add('visible');
	}
	else
	{
		repeatPasswordValue.classList.remove('error');
		errorSpan.classList.remove('visible');
	}
	if (isSuccess === false)
	{
		passwordValue.value = '';
		repeatPasswordValue.value = '';
	}
	else
	{
		let formData = new URLSearchParams();
		values.forEach(field => {
			formData.append(field.name,field.value.value);
		});
		return fetch('/register', {
			method: 'post',
			body: formData,
		});
	}
}

function stringFormat(value, args)
{
	let template = args.template || '';
	let minLength = args.minLength || 1;
	let maxLength = args.maxLength || 20;
	let regex = new RegExp(template);
	let match = value.match(template) || [];
	return match[0] === value && value.length >= minLength && value.length <= maxLength;

}

function repeatPassword(firstPassword, secondPassword)
{
	return firstPassword === secondPassword;
}

const submitButton = document.getElementById('submit').addEventListener('click', () => {
	checkInputs();
});



