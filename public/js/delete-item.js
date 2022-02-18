async function deleteItem(id, csrf){
	let data = new FormData();
	data.append('csrf_token', csrf);
	return fetch('/admin/deactivateItem/' + id, {method: 'POST', body: data});
}

let deleteBtns = document.querySelectorAll('.btn-delete');
deleteBtns.forEach(el => {
	el.addEventListener('click', (event) => {
		let itemId = el.parentNode.parentNode.querySelector('[name=item-id]').value;
		let csrf = el.parentElement.parentElement.querySelector('[name=csrf_token]').value;
		deleteItem(itemId, csrf).then(location.reload());
	});
});