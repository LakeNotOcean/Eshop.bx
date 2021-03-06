createTemplate().then();

createItemTemplate();

let btnDeleteItem = document.querySelector('.btn-delete-item');

if (btnDeleteItem) {
	btnDeleteItem.addEventListener('click', () => {
		alertDialogDelete('Удаление товара', 'Вы точно хотите удалить этот товар?', () => {
			let postBody = new FormData();
			let token = document.querySelector('.token');
			postBody.append(token.name, token.value);

			let idInput = document.querySelector('[name=item-id]');
			let itemId = idInput.value;
			fetch('/admin/deleteItem/' + itemId, {
				method: 'post',
				body: postBody
			}).then((r) => {
				if (r.ok) {
					window.location = r.url;
				} else {
					showPopup('Не удалось удалить товар')
				}
			});
		});
	});
}

async function createItemTemplate()
{
	let idInput = document.querySelector('[name=item-id]');
	if (idInput == null)
	{
		return;
	}
	let itemId = idInput.value;
	let categories = await loadCategoryAndSpecByItemId(itemId);
	let firstBtn = document.querySelector('.add-category');
	for (let catId in categories)
	{
		let category = await createCategory(catId);
		let catDiv = firstBtn.parentNode.insertBefore(category, firstBtn);
		let specAddBtn = catDiv.querySelector('.category .btn-add');
		for (let specId in categories[catId][1])
		{
			let spec = await createSpec(specAddBtn, specId, categories[catId][1][specId][1]);
			specAddBtn.parentNode.insertBefore(spec, specAddBtn);
		}
	}
}

function resetForm(form)
{
	form.reset();
	resetPreview();
	let categories = form.querySelectorAll('.category');
	categories.forEach(category => category.remove());
	createTemplate().then();
	createItemTemplate();
}

function sendPost(item)
{
	return fetch('/admin/addItem', {
		method: 'post',
		body: item,
	});
}

document.querySelector('.form-add').addEventListener('submit', (e) => {
	try
	{
		e.preventDefault();
		let item = new FormData();

		const tagsContainer = document.querySelector('.tags-container');
		const tagNodes = tagsContainer.querySelectorAll('.input-tag');
		let tags = "";
		for (let tagName of tagNodes) {
			tags += tagName.innerText + ',';
		}
		if (tags.length === 0) {
			throw new Error('Не добавлены теги товара');
		}
		tags = tags.slice(0, -1);
		item.append('item-tags', tags);

		let catNodes = document.querySelectorAll('.category');
		catNodes.forEach((el) => {
			let catId = el.querySelector('.input-category').value;
			let specNode = el.querySelectorAll('.input-spec-name');
			specNode.forEach(spec => {
				let specId = spec.value;
				let specValue = spec.parentNode.nextSibling.value;
				if (specValue === '')
				{
					throw new Error('Поле "' + spec.options[spec.options.selectedIndex].textContent + '" не заполнено!');
				}
				item.append('specs[' + catId + '][' + specId + ']', specValue);
			});
		});
		let mainFields = document.querySelectorAll('.main-fields input');
		mainFields.forEach(field => {
			if (field.name !== 'item-tags') {
				if(field.value === '')
					throw new Error('Поле "' + field.previousElementSibling.textContent + '" не заполнено!')
				item.append(field.name, field.value);
			}
		});
		if ((new URLSearchParams(document.location.search)).has('item-type'))
		{
			item.append('item-type', (new URLSearchParams(document.location.search)).get('item-type'));
		}
		let mainImgInput = document.querySelector('[name=main-image]');
		if(mainImgInput.files.length === 0 && document.querySelector('[alt=main-image]').name === '')
			throw new Error('Главная картинка не прикреплена');
		item.append('main-image', mainImgInput.files[0]);
		let otherImgInput = document.querySelector('[name=other-images]');
		if(otherImgInput.files.length === 0 && document.querySelectorAll('[alt=other-image]').length === 0)
			throw new Error('Нужна хотя бы одна дополнительная картинка');
		for (let file of otherImgInput.files)
		{
			item.append('other-images[]', file);
		}
		sendPost(item).then((r) => {
			if (r.redirected)
			{
				setTimeout(() => {
					location.href = r.url;
				}, 500);
				showPopup('Товар добавлен');
			}
			else
			{
				showPopup('Товар не удалось добавить.');
			}
		});
	}
	catch (e) {
		alertDialog('Внимание', e.message)
	}
});

async function createTemplate()
{
	if (!(new URLSearchParams(document.location.search)).get('item-type'))
	{
		return;
	}
	templateCategory = await loadCategoryAndSpecByType((new URLSearchParams(document.location.search)).get('item-type'));
	let firstBtn = document.querySelector('.add-category');
	for (let catId in templateCategory)
	{
		let category = await createCategory(catId);
		let catDiv = firstBtn.parentNode.insertBefore(category, firstBtn);
		let specAddBtn = catDiv.querySelector('.category .btn-add');
		for (let specId in templateCategory[catId][1])
		{
			let spec = await createSpec(specAddBtn, specId);
			specAddBtn.parentNode.insertBefore(spec, specAddBtn);
		}
	}
}
