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
			body: formData
		}
	).then(
		response => response.json()
		)
		.then(
			function(json) {
				result = json.success;
			}
		);

	return result;
}

/**
 *
 * @param {Element} form
 * @param {{price: number, count: number, id: number}[]} itemsInfo
 * @return {Promise<void>}
 */
export async function sendOrderData(form, itemsInfo)
{

}