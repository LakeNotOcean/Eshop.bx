import { getCSRFFromForm } from '../csrf.js';

/**
 *
 * @param {number} itemId
 * @param {Element} form
 *
 * @return boolean
 */
export async function deleteItemFromCart(itemId, form)
{
	let csrf = getCSRFFromForm(form);
	let formData = new FormData();
	formData.append(csrf.name, csrf.value);
	formData.append('item-id', itemId.toString());
	let result;
	await fetch(
		'/deleteItemFromCart',
		{
			method: 'post',
			body: formData,
		},
	).then(
			response => response.json(),
		)
		.then(
			function(json) {
				result = json.success;
			},
		);

	return result;
}

/**
 *
 * @param {Element} form
 * @param {{count: number, id: number}[]} itemsInfo
 */
export function sendOrderData(form, itemsInfo)
{
	let formData = new FormData();

	let csrf = getCSRFFromForm(form);
	formData.append(csrf.name, csrf.value);
	let firstName = form.querySelector('#first-name').value;
	formData.append('first-name', firstName);

	let secondName = form.querySelector('#second-name').value;
	formData.append('second-name', secondName);

	let phone = form.querySelector('#phone').value;
	formData.append('phone', phone);

	let email = form.querySelector('#email').value;
	formData.append('email', email);

	let comment = form.querySelector('#comment').value;
	formData.append('comment', comment);

	formData.append('items', JSON.stringify(itemsInfo));
	return fetch(
		'/finishOrder',
		{
			method: 'post',
			body: formData
		})
}