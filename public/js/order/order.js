import { wordEndingResolver } from '../lib/wordProcessor.js';
import { deleteItemFromCart, sendOrderData } from './send-order-data.js';
import { validateFields, validators } from '../lib/validators.js';

const orderItems = document.querySelectorAll('.order-item');

const orderHeaderInfoElement = document.querySelector('.order-info-message');
const orderSummaryElement = document.querySelector('.order-summary');

const ordersPriceText = orderHeaderInfoElement.textContent;

const numbers = ordersPriceText.match(/\d+/g);
let [itemsAmount, orderPrice] = [0, 0];
if (numbers !== null)
{
	[itemsAmount, orderPrice] = numbers.map(num => parseInt(num));
	document.querySelector('input[type="submit"]').addEventListener('click', submitOrder);
}

let itemsInfo = [];

orderItems.forEach(
	function(itemElement) {
		let itemId = parseInt(itemElement.getAttribute('value'));
		let itemInfo = {
			id: itemId,
			count: 1,
			price: getItemPrice(itemId),
		};
		itemElement.querySelector('.item-count-add').addEventListener(
			'click',
			getIncreaseItemCountEventHandler(itemInfo, itemElement),
		);
		itemElement.querySelector('.item-count-reduce').addEventListener(
			'click',
			getReduceItemCountEventHandler(itemInfo, itemElement),
		);
		itemsInfo.push(itemInfo);
	},
);

/**
 * @param {{price: number, count: number, id: number}} itemInfo
 * @param {Element} itemElement
 */
function getIncreaseItemCountEventHandler(itemInfo, itemElement)
{
	return function(event) {
		orderPrice = orderPrice + itemInfo.price;
		changeItemCount(itemInfo, 1);
		updateAggregatedOrderData(++itemsAmount, orderPrice);
	};
}

/**
 * @param {{price: number, count: number, id: number}} itemInfo
 * @param {Element} itemElement
 */
function getReduceItemCountEventHandler(itemInfo, itemElement)
{
	return async function(event) {
		if (itemInfo.count === 1)
		{
			alertDialogDelete('Удаление товара', 'Вы точно хотите удалить этот товар из коризны?',
				() =>
				{
					itemsInfo = itemsInfo.filter(x => x.id !== itemInfo.id);
					deleteItemElement(itemInfo.id);
					orderPrice = orderPrice - itemInfo.price;
					updateAggregatedOrderData(--itemsAmount, orderPrice);
					deleteItemFromCart(itemInfo.id, document.querySelector('.user-data'));
					if (itemsInfo.length === 0)
					{
						setEmptyOrderState();
					}
				});
			return;
		}
		orderPrice = orderPrice - itemInfo.price;
		changeItemCount(itemInfo, -1);
		updateAggregatedOrderData(--itemsAmount, orderPrice);
	};
}

function setEmptyOrderState()
{
	document.querySelector('.user-data').remove();
	document.querySelector('.cart-empty').removeAttribute('hidden');
}

function deleteItemElement(itemId)
{
	getItemElementById(itemId).remove();
}

function getItemElementById(itemId)
{
	return document.querySelector(`.order-item[value='${itemId}']`);
}

async function submitOrder(event)
{
	let form = document.querySelector('.user-data');

	const firstName = form.querySelector('#first-name').value;
	const secondName = form.querySelector('#second-name').value;
	const phone = form.querySelector('#phone').value;
	const email = form.querySelector('#email').value;

	let validationErrors = validateFields([
		{
			fieldName: 'firstName',
			validatorName: validators.names,
			data: firstName,
		},
		{
			fieldName: 'secondNname',
			validatorName: validators.names,
			data: secondName,
		},
		{
			fieldName: 'phone',
			validatorName: validators.phone,
			data: phone,
		},
		{
			fieldName: 'email',
			validatorName: validators.email,
			data: email,
		}
	]);

	const errorsContainerElement = form.querySelector('.errors-container');
	if (validationErrors.length !== 0)
	{
		showValidationErrors(validationErrors, errorsContainerElement);
	}
	else
	{
		await sendOrderData(form, itemsInfo).then(
			response => {
				if (response.redirected)
				{
					window.location.href = response.url;
				}
				else if (!response.ok)
				{
					response.json().then(
						json => {
							showValidationErrors(json, errorsContainerElement);
						}
					)
				}
				else {
					console.error(`Что-то пошло координально не так!`);
				}
			},
		)
	}
}

function getItemCountElement(itemId)
{
	return getItemElementById(itemId).querySelector('.item-count');
}

/**
 *
 * @param {{price: number, count: number, id: number}} itemInfo
 * @param {number} delta
 */
function changeItemCount(itemInfo, delta)
{
	let countElement = getItemCountElement(itemInfo.id);
	itemInfo.count += delta;
	countElement.textContent = (parseInt(countElement.textContent) + delta).toString();
}

/**
 *
 * @param {{fieldName: string, errors: string[]}[]} errors
 * @param {Element} errorsPlace
 */
function showValidationErrors(errors, errorsPlace)
{
	const fieldNameToRepresentedName = {
		'firstName': 'Имя',
		'secondName': 'Фамилия',
		'phone': 'Телефон',
		'email': 'E-mail'
	};
	errorsPlace.innerHTML = '';
	errors.forEach(
		(errorByField) => {
			errorByField.errors.forEach(
				(error) => {
					let errorInfoNode = document.createElement('p');
					errorInfoNode.classList.add('error-text');
					errorInfoNode.textContent = fieldNameToRepresentedName[errorByField.fieldName] + ': ' + error;

					errorsPlace.appendChild(errorInfoNode);
				}
			)
		}
	);
}

/**
 *
 * @param {number} itemId
 */
function getItemPriceElement(itemId)
{
	return getItemElementById(itemId).querySelector('.item-price');
}

function getItemPrice(itemId)
{
	return parseInt(getItemPriceElement(itemId).textContent);
}

function updateAggregatedOrderData(itemsAmount, orderPrice)
{
	let itemsText = wordEndingResolver(itemsAmount, ['товар', 'товара', 'товаров']);
	orderHeaderInfoElement.textContent = `Оформление заказа: ${itemsAmount} 
	${itemsText} за ${orderPrice} ₽`;
	orderSummaryElement.textContent = `Итого: ${itemsAmount} ${itemsText} на сумму ${orderPrice} ₽`;
}
