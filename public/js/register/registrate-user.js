const passwordValue = document.getElementById('password');
const repeatPasswordValue = document.getElementById('repeatPassword');

const values = [
	[loginValue = document.getElementById('login'),
		args = { template: '^[a-zA-Z0-9]+$', minLength: 3, maxLength: 20 }],
	[firstName = document.getElementById('firstName'),
		args = { template: '^(?:[a-zA-z]+|[а-яА-Яё]+)$', minLength: 1, maxLength: 30 }],
	[secondName = document.getElementById('secondName'),
		args = { template: '^(?:[a-zA-z]+|[а-яА-Яё]+)$', minLength: 1, maxLength: 30 }],
	[passwordValue,
		args = { template: '^[a-zA-Z0-9]+$', minLength: 5, maxLength: 50 }],
	[email = document.getElementById('email'),
		args = { template: '^(([^<>()[\\]\\.,;:\\s@\\"]+(\\.[^<>()[\\]\\.,;:\\s@\\"]+)*)|(\\".+\\"))@(([^<>()[\\]\\.,;:\\s@\\"]+\\.)+[^<>()[\\]\\.,;:\\s@\\"]{2,})$', minLength: 5, maxLength: 50 }],
	[phone = document.getElementById('phone'),
		args = { template: '^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\\s/0-9]*$' }],
];

function checkInputs()
{
	let isSuccess = true;
	values.forEach(field => {
		const value = field[0].value;
		const args = field[1];
		const result = stringFormat(value,args);
		const errorSpan = document.querySelector(`[data-source="${field[0].id}"]`);
		if (result === true)
		{
			field[0].classList.remove('error');
			field[0].classList.add('success');
			errorSpan.classList.remove('visible');
		}
		else
		{
			isSuccess = false;
			field[0].classList.add('error');
			field[0].classList.remove('success');
			errorSpan.classList.add('visible');
		}
	});
	const errorSpan = document.querySelector(`[data-source="repeatPassword"]`);
	errorSpan.classList.remove('visible');
	if (!repeatPassword(passwordValue.value, repeatPasswordValue.value))
	{
		isSuccess = false;
		repeatPasswordValue.classList.add('error');
		errorSpan.classList.add('visible');
	}
	if (isSuccess === false)
	{
		errorSpan.classList.remove('visible');
		passwordValue.value = '';
		repeatPasswordValue.value = '';
	}

}

function stringFormat(value,args)
{
	let template=args.template || "";
	let minLength=args.minLength || 1;
	let maxLength=args.maxLength || 20;
	let regex=new RegExp(template);
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



