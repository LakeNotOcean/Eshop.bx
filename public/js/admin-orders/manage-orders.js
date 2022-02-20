
let orderStatuses = document.querySelectorAll('.order-status');

for (let orderStatus of orderStatuses)
{
	orderStatus.addEventListener('change', () => {
		const id = orderStatus.id.match(/\d+/)[0];
		const status = orderStatus.value;
		let postBody = new FormData();
		postBody.append('order-id', id);
		postBody.append('order-status', status);

		let orderList = document.querySelector('.order-list');
		let token = document.querySelector('input');
		postBody.append(token.name, token.value);

		fetch('/admin/getOrders', {
			method: 'post',
			body: postBody
		}).then((r) => {
			if (r.ok) {
				popup('Статус заказа обновлён');
			} else {
				popup('Статус заказа не удалось обновить')
			}
		});
	})
}

function popup(text) {
	let popup = document.createElement('div');
	popup.textContent = text;
	popup.classList.add('popup');
	document.querySelector('body').append(popup);
	setTimeout(() => {
		popup.classList.add('hidden');
	}, 2000);
	setTimeout(() => {
		popup.remove();
	}, 2100);
}
