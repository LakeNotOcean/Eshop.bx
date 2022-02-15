createTemplate().then();

function resetForm(form){
	form.reset();
	let categories = form.querySelectorAll('.category');
	categories.forEach(category => category.remove());
	createTemplate().then();
}

function popup(text){
	let popup = document.createElement('div');
	popup.textContent = text;
	popup.classList.add('popup');
	document.querySelector('.form-container').append(popup);
	setTimeout(() => {
		popup.remove();
	}, 2000);
}

function sendPost(item){
	return fetch('/admin/addItem', {
		method: 'post',
		body: item
	});
}

document.querySelector('.form-add').addEventListener('submit', (e) => {
	e.preventDefault();
	let item = new URLSearchParams();
	let catNodes = document.querySelectorAll('.category');
	catNodes.forEach((el) => {
		let catId = el.querySelector('.input-category').value;
		let specNode = el.querySelectorAll('.input-spec-name');
		specNode.forEach(spec => {
			let specId = spec.value;
			let specValue = spec.parentNode.nextSibling.value;
			item.append('specs[' + catId + '][' + specId + ']', specValue);
		})
	});
	let mainFields = document.querySelectorAll('.main-fields input');
	mainFields.forEach(field => {
		item.append(field.name, field.value);
	});
	item.append('item-type', (new URLSearchParams(document.location.search)).get('item-type'))
	sendPost(item).then((r) => {
		if(r.ok){
			popup('Товар добавлен!');
			resetForm(e.target);
		}else {
			popup('Товар не удалось добавить.')
		}
	});
});



async function createTemplate()
{
	if(!(new URLSearchParams(document.location.search)).get('item-type')) return;
	templateCategory = await loadCategoryAndSpecByType((new URLSearchParams(document.location.search)).get('item-type'));
	let firstBtn = document.querySelector('.add-category');
	for (let catId in templateCategory)
	{
		let category = await createCategory(catId);
		let catDiv = firstBtn.parentNode.insertBefore(category, firstBtn);
		let specAddBtn = catDiv.querySelector('.category .add');
		specAddBtn.addEventListener('click', async () => {
			let spec = await createSpec(specAddBtn);
			specAddBtn.parentNode.insertBefore(spec, specAddBtn);
		});
		for (let specId in templateCategory[catId][1])
		{
			let spec = await createSpec(specAddBtn, specId);
			specAddBtn.parentNode.insertBefore(spec, specAddBtn);
		}
	}
}