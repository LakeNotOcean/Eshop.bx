
let orderStatuses = document.querySelectorAll('.order-status');

for (let orderStatus of orderStatuses)
{
	orderStatus.addEventListener('change', () => {
		const id = orderStatus.id.match(/\d+/)[0];
		const status = orderStatus.value;
		let postBody = new FormData();
		postBody.append('order-id', id);
		postBody.append('order-status', status);

		let token = document.querySelector('.token');
		postBody.append(token.name, token.value);

		fetch('/admin/changeOrderStatus', {
			method: 'post',
			body: postBody
		}).then((r) => {
			if (r.ok) {
				showPopup('Статус заказа обновлён');
			} else {
				showPopup('Статус заказа не удалось обновить')
			}
		});
	})
}

let deleteButtons = document.querySelectorAll('.btn-delete');

for (let btnDelete of deleteButtons)
{
	btnDelete.addEventListener('click', () => {
		const id = btnDelete.id.match(/\d+/)[0];
		let postBody = new FormData();
		postBody.append('order-id', id);

		let token = document.querySelector('.token');
		postBody.append(token.name, token.value);

		fetch('/admin/deleteOrder', {
			method: 'post',
			body: postBody
		}).then((r) => {
			if (r.ok) {
				showPopup('Заказ удалён');
				btnDelete.parentNode.parentNode.remove();
			} else {
				showPopup('Не удалось удалить заказ')
			}
		});
	})
}
