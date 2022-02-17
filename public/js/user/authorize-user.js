import {checkInputs,makePostQuery} from './help-user.js';

const inputFields = [
	{
		doc: document.getElementById('login'),
		args: { template: '^[a-zA-Z0-9]+$', minLength: 3, maxLength: 20 }, name: 'login',
	},
	{
		doc: document.getElementById('password'),
		args: { template: '^[a-zA-Z0-9]+$', minLength: 5, maxLength: 50 }, name: 'password',
	},
];


async function checkReg()
{
	let isSuccess = checkInputs(inputFields);
	if (isSuccess)
	{
		await makePostQuery(inputFields, '/login', 'Вы успешны авторизированы', 'Неверное имя пользователя и/или пароль');
	}

}

document.getElementById('submit').addEventListener('click', () => {
	checkReg().then();
},false);