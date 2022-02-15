let allCategory = {};
let templateCategory = {};

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
	return fetch('/testPost', {
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
	sendPost(item).then((r) => {
		if(r.ok){
			popup('Товар добавлен!');
			resetForm(e.target);
		}else {
			popup('Товар не удалось добавить.')
		}
	});
});

async function getCategory()
{
	if (Object.keys(allCategory).length != 0)
	{
		return allCategory;
	}
	allCategory = await loadAllCategory();
	return allCategory;
}

async function getSpecification(catId)
{
	if (catId in allCategory)
	{
		return allCategory[catId];
	}
	allCategory = await loadAllCategory();
	return allCategory[catId];
}

async function createTemplate()
{
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

async function getSpecOption(select, catId)
{
	while (select.firstChild) select.lastChild.remove();
	let specs = await getSpecification(catId);
	for (let specId in specs[1])
	{
		let option = document.createElement('option');
		option.value = specId;
		option.text = specs[1][specId];
		select.append(option);
	}

}

function changeChild(parent, catId)
{
	nodeList = parent.parentElement.parentElement.parentElement.querySelectorAll('.input-spec-name');
	nodeList.forEach(node => {
		getSpecOption(node, catId);
	});
}

async function createSpec(btn, specId = null)
{
	let specDiv = document.createElement('div');
	specDiv.classList.add('spec');

	let fieldDiv = document.createElement('div');
	fieldDiv.classList.add('field');
	let specNameInput = document.createElement('select');
	//specNameInput.addEventListener('change', event => changeSpecValueInput(event.target));

	let specValueInput = document.createElement('input');
	specValueInput.placeholder = 'Ввести значение спецификации';

	let category = btn.parentNode;
	let inputCategory = category.querySelector('.input-category');
	let categoryId = inputCategory.value;

	await getSpecOption(specNameInput, categoryId);

	if (specId != null)
	{
		specNameInput.value = specId;
	}

	specNameInput.classList.add('input-spec-name');
	fieldDiv.append(specNameInput);

	let btnDeleteDiv = document.createElement('div');
	btnDeleteDiv.classList.add('btn', 'delete');
	btnDeleteDiv.innerText = 'Удалить';

	btnDeleteDiv.addEventListener('click', () => {
		btnDeleteDiv.parentNode.parentNode.removeChild(btnDeleteDiv.parentNode);
	});

	specDiv.append(fieldDiv, specValueInput, btnDeleteDiv);
	return specDiv;
}

let addCategory = document.querySelectorAll('.add-category');
for (let btnAddCategory of addCategory)
{
	btnAddCategory.addEventListener('click', async () => {
		let cat = await createCategory();
		btnAddCategory.parentNode.insertBefore(cat, btnAddCategory);
	});
}

async function createCategory(catId = null)
{
	let categoryDiv = document.createElement('div');
	categoryDiv.classList.add('category');

	let categoryFieldDiv = document.createElement('div');
	categoryFieldDiv.classList.add('category-field');

	let fieldDiv = document.createElement('div');
	fieldDiv.classList.add('field');

	let inputCategoryInput = document.createElement('select');
	inputCategoryInput.classList.add('input-category');

	let categories = await getCategory();

	for (let categoryId in categories)
	{
		let option = document.createElement('option');
		option.value = categoryId;
		option.text = categories[categoryId][0];
		inputCategoryInput.append(option);
		if (catId != null)
		{
			inputCategoryInput.value = catId;
		}
	}

	inputCategoryInput.addEventListener('change', (event) => {
		changeChild(event.target, event.target.value);
	});

	fieldDiv.append(inputCategoryInput);

	let btnDeleteDiv = document.createElement('div');
	btnDeleteDiv.classList.add('btn', 'delete');
	btnDeleteDiv.innerText = 'Удалить';

	btnDeleteDiv.addEventListener('click', () => {
		btnDeleteDiv.parentNode.parentNode.parentNode.removeChild(btnDeleteDiv.parentNode.parentNode);
	});

	categoryFieldDiv.append(fieldDiv, btnDeleteDiv);

	let btnAddDiv = document.createElement('div');
	btnAddDiv.classList.add('btn', 'add');
	btnAddDiv.innerText = 'Добавить спецификацию';

	btnAddDiv.addEventListener('click', async () => {
		let spec = await createSpec(btnAddDiv);
		btnAddDiv.parentNode.insertBefore(spec, btnAddDiv);
	});

	categoryDiv.append(categoryFieldDiv, btnAddDiv);

	return categoryDiv;
}
