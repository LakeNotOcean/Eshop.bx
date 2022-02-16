createTemplate().then();

createItemTemplate()
async function createItemTemplate(){
	let idInput = document.querySelector('[name=item-id]');
	if(idInput == null) return;
	let itemId = idInput.value;
	let categories = await loadCategoryAndSpecByItemId(itemId);
	let firstBtn = document.querySelector('.add-category');
	for (let catId in categories){
		let category = await createCategory(catId);
		let catDiv = firstBtn.parentNode.insertBefore(category, firstBtn);
		let specAddBtn = catDiv.querySelector('.category .btn-add');
		for (let specId in categories[catId][1]) {
			let spec = await createSpec(specAddBtn, specId, categories[catId][1][specId][1]);
			specAddBtn.parentNode.insertBefore(spec, specAddBtn);
		}
	}
}

function resetForm(form) {
	form.reset();
	resetPreview();
	let categories = form.querySelectorAll('.category');
	categories.forEach(category => category.remove());
	createTemplate().then();
	createItemTemplate();
}

function popup(text) {
	let popup = document.createElement('div');
	popup.textContent = text;
	popup.classList.add('popup');
	document.querySelector('.form-container').append(popup);
	setTimeout(() => {
		popup.classList.add('hidden');
	}, 2000);
	setTimeout(() => {
		popup.remove();
	}, 2100);
}

function sendPost(item) {
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
	if((new URLSearchParams(document.location.search)).has('item-type'))
		item.append('item-type', (new URLSearchParams(document.location.search)).get('item-type'))
	sendPost(item).then((r) => {
		if(r.ok) {
			popup('Товар добавлен');
			resetForm(e.target);
		} else {
			popup('Товар не удалось добавить.')
		}
	});
});


async function createTemplate() {
	if(!(new URLSearchParams(document.location.search)).get('item-type')) return;
	templateCategory = await loadCategoryAndSpecByType((new URLSearchParams(document.location.search)).get('item-type'));
	let firstBtn = document.querySelector('.add-category');
	for (let catId in templateCategory) {
		let category = await createCategory(catId);
		let catDiv = firstBtn.parentNode.insertBefore(category, firstBtn);
		let specAddBtn = catDiv.querySelector('.category .btn-add');
		for (let specId in templateCategory[catId][1]) {
			let spec = await createSpec(specAddBtn, specId);
			specAddBtn.parentNode.insertBefore(spec, specAddBtn);
		}
	}
}
