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

async function checkReg(e)
{
	let isSuccess = checkInputs(inputFields);
	if (!isSuccess)
	{
		e.preventDefault()
		return false;
	}
	return true;
}

let errorsContainer = document.querySelector('.errors-container');
document.querySelector('.register-fields').addEventListener('submit', async (e) => {
	e.preventDefault();
	errorsContainer.innerHTML = '';
	if (await checkReg(e)) {
		sendSimpleForm(e.target, '/login', 'post').then(r =>{
			if(r.redirected){
				location.href = r.url;
			} else {
				r.json().then(json =>{
					let errorCategories = printError(errorsContainer, json);
					for(let category of errorCategories){
						let input = document.querySelector('[name=' + category + ']');
						input.classList.remove('success');
						input.classList.add('error');
					}
				})
			}
		});
	}
});
