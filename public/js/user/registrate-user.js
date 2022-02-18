import {checkInputs,makePostQuery} from './help-user.js';

const passwordValue = document.getElementById('password');
const repeatPasswordValue = document.getElementById('repeatPassword');


const inputFields = [
	{
		doc: document.getElementById('login'),
		args: { template: '^[a-zA-Z0-9]+$', minLength: 3, maxLength: 20 }, name: 'login',
	},
	{
		doc: document.getElementById('firstName'),
		args: { template: '^(?:[a-zA-z]+|[а-яА-Яё]+)$', minLength: 1, maxLength: 30 }, name: 'firstName',
	},
	{
		doc: document.getElementById('secondName'),
		args: { template: '^(?:[a-zA-z]+|[а-яА-Яё]+)$', minLength: 1, maxLength: 30 }, name: 'secondName',
	},
	{
		doc: passwordValue,
		args: { template: '^[a-zA-Z0-9]+$', minLength: 5, maxLength: 50 }, name: 'password',
	},
	{
		doc: document.getElementById('email'),
		args: {
			template: '^(([^<>()[\\]\\.,;:\\s@\\"]+(\\.[^<>()[\\]\\.,;:\\s@\\"]+)*)|(\\".+\\"))@(([^<>()[\\]\\.,;:\\s@\\"]+\\.)+[^<>()[\\]\\.,;:\\s@\\"]{2,})$',
			minLength: 5,
			maxLength: 50,
		}, name: 'email',
	},
	{
		doc: document.getElementById('phone'),
		args: { template: '^(\\+7|7|8)?[\\s\\-]?\\(?[489][0-9]{2}\\)?[\\s\\-]?[0-9]{3}[\\s\\-]?[0-9]{2}[\\s\\-]?[0-9]{2}$' },
		name: 'phone',
	},
];

async function checkReg(e)
{
	let isSuccess = checkInputs(inputFields);
	isSuccess = checkPasswords(isSuccess);
	console.log(isSuccess);
	if (!isSuccess)
	{
		e.preventDefault()
	}
}

function checkPasswords(isSuccess = false)
{
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
	return isSuccess;
}

function repeatPassword(firstPassword, secondPassword)
{
	return firstPassword === secondPassword;
}

document.getElementById('submit').addEventListener('click', (e) => {
	checkReg(e).then();
},false);
