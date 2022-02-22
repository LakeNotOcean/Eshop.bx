async function deactivateItem(id, csrf){
	let data = new FormData();
	data.append('csrf_token', csrf);
	return fetch('/admin/deactivateItem/' + id, {method: 'POST', body: data});
}

async function activateItem(id, csrf){
	let data = new FormData();
	data.append('csrf_token', csrf);
	return fetch('/admin/activateItem/' + id, {method: 'POST', body: data});
}

let deleteBtns = document.querySelectorAll('.btn-deactivate');
let returnBtns = document.querySelectorAll('.btn-return');
deleteBtns.forEach(el => {
	el.addEventListener('click', (event) => {
		let itemId = el.parentNode.parentNode.querySelector('[name=item-id]').value;
		let csrf = el.parentElement.parentElement.querySelector('[name=csrf_token]').value;
		deactivateItem(itemId, csrf).then((r) => {
			if(r.ok)
				setTimeout(() => location.reload(), 5);
			else
				popup('Не удалось');
		});
	});
});

returnBtns.forEach(el =>{
	el.addEventListener('click', () => {
		let itemId = el.parentNode.parentNode.querySelector('[name=item-id]').value;
		let csrf = el.parentElement.parentElement.querySelector('[name=csrf_token]').value;
		activateItem(itemId, csrf).then((r) => {
			if(r.ok)
				setTimeout(() => location.reload(), 5);
			else
				popup('Не удалось');
		});
	});
});