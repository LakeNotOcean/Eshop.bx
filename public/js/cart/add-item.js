import { getCSRFFromForm } from '../csrf.js';

const itemIdPostKey = 'item-id';
const btnAddedClass = 'btn-item-added';

let form = document.getElementById('item-cart-container');
let itemInput = form.querySelector('.item-id');
let sendItemIdButton = form.querySelector('#send-item-id');

let csrf = getCSRFFromForm(form);
let itemId = parseInt(itemInput.getAttribute('value'), 10);

let formBody = new FormData();
formBody.append(csrf.name, csrf.value);
formBody.append(itemIdPostKey, itemId.toString(10))

if (form.querySelector('#cart-add-item') !== null)
{
	sendItemIdButton.addEventListener('click', addItemToCartEventHandler);
}
else
{
	sendItemIdButton.addEventListener('click', deleteItemFromCartEventHandler)
}

function deleteItemFromCartEventHandler(event)
{
	fetch('/deleteItemFromCart', {
		method:'post',
		body: formBody
	}).then(function(response) {
		return response.json()
	}).then(function(jsonResponse)
	{
		if (jsonResponse.success)
		{
			updateCartSize(jsonResponse.cartSize);
			setButtonToAddToCartStatement(form)
		}
		else
		{
			showPopup('Не удалось удалить товар из корзины')
		}
	})
}

function addItemToCartEventHandler(event)
{
	fetch('/addItemToCart',
		{
			method:'post',
			body: formBody
		}).then(
			function(response) {
				return response.json();
			}
	).then(
		function(jsonBody)
		{
			if (jsonBody.success)
			{
				updateCartSize(jsonBody.cartSize);
				setButtonInAddedToCartStatement(form)
			}
			else
			{
				showPopup('Не удалось добавить товар в корзину')
			}
		}
	)
}

function setButtonToAddToCartStatement(form)
{
	form.innerHTML = '';
	form.appendChild(csrf.input);
	form.appendChild(itemInput);

	sendItemIdButton.classList.remove(btnAddedClass);
	sendItemIdButton.removeEventListener('click', deleteItemFromCartEventHandler);
	sendItemIdButton.addEventListener('click', addItemToCartEventHandler);
	sendItemIdButton.textContent = 'Добавить товар в корзину';
	form.appendChild(sendItemIdButton);
}

function setButtonInAddedToCartStatement(form)
{
	form.innerHTML = '';
	form.appendChild(csrf.input);
	form.appendChild(itemInput);

	sendItemIdButton.classList.add(btnAddedClass);
	sendItemIdButton.removeEventListener('click', addItemToCartEventHandler);
	sendItemIdButton.addEventListener('click', deleteItemFromCartEventHandler);
	sendItemIdButton.textContent = 'Удалить товар из корзины';
	form.appendChild(sendItemIdButton);
}

function updateCartSize(size) {
	document.querySelector('.cart').setAttribute('data-cart-size', size);
}
