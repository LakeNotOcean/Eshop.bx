async function deleteItem(id){
	return fetch('/admin/deactivateItem/' + id, {method: 'POST'});
}

let deleteBtns = document.querySelectorAll('.btn-delete');
deleteBtns.forEach(el => {
	el.addEventListener('click', (event) => {
		let itemId = el.parentNode.parentNode.querySelector('[name=item-id]').value;
		deleteItem(itemId).then(location.reload());
	});
});