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

function changeChild(parent, catId){
	nodeList = parent.parentElement.parentElement.parentElement.querySelectorAll('.input-spec-name');
	nodeList.forEach(node => {
		getSpecOption(node, catId);
	});
}

let deleteSpec = document.querySelectorAll('.spec .btn-delete');
for (let btnDeleteSpec of deleteSpec) {
	btnDeleteSpec.addEventListener('click', () => {
		btnDeleteSpec.parentNode.parentNode.removeChild(btnDeleteSpec.parentNode);
	});
}

let deleteCategory = document.querySelectorAll('.category-field .btn-delete');
for (let btnDeleteCategory of deleteCategory) {
	btnDeleteCategory.addEventListener('click', () => {
		btnDeleteCategory.parentNode.parentNode.parentNode.removeChild(btnDeleteCategory.parentNode.parentNode);
	});
}

let addSpec = document.querySelectorAll('.category .btn-add');
for (let btnAddSpec of addSpec) {
	btnAddSpec.addEventListener('click', async () => {
		let spec = await createSpec(btnAddSpec);
		btnAddSpec.parentNode.insertBefore(spec, btnAddSpec);
	});
}

async function createSpec(btn) {
	let specDiv = document.createElement('div');
	specDiv.classList.add('spec');

	let fieldDiv = document.createElement('div');
	fieldDiv.classList.add('field');
	let specNameInput = document.createElement('select');
	specNameInput.name = 'template-specs[]';

	let category = btn.parentNode;
	let inputCategory = category.querySelector('.input-category');
	let categoryId = inputCategory.value;

	await getSpecOption(specNameInput, categoryId);


	specNameInput.classList.add('input-spec-name');
	fieldDiv.append(specNameInput);

	let btnDeleteDiv = document.createElement('div');
	btnDeleteDiv.classList.add('btn', 'btn-delete');
	btnDeleteDiv.innerText = "Удалить";

	btnDeleteDiv.addEventListener('click', () => {
		btnDeleteDiv.parentNode.parentNode.removeChild(btnDeleteDiv.parentNode);
	});

	specDiv.append(fieldDiv, btnDeleteDiv);
	return specDiv;
}

let addCategory = document.querySelectorAll('.add-category');
for (let btnAddCategory of addCategory) {
	btnAddCategory.addEventListener('click', async () => {
		let cat = await createCategory();
		btnAddCategory.parentNode.insertBefore(cat, btnAddCategory);
	});
}

async function createCategory() {
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
	}

	inputCategoryInput.addEventListener('change', (event)=>{
		changeChild(event.target, event.target.value);
	});

	fieldDiv.append(inputCategoryInput);

	let btnDeleteDiv = document.createElement('div');
	btnDeleteDiv.classList.add('btn', 'btn-delete');
	btnDeleteDiv.innerText = "Удалить";

	btnDeleteDiv.addEventListener('click', () => {
		btnDeleteDiv.parentNode.parentNode.parentNode.removeChild(btnDeleteDiv.parentNode.parentNode);
	});

	categoryFieldDiv.append(fieldDiv, btnDeleteDiv);

	let btnAddDiv = document.createElement('div');
	btnAddDiv.classList.add('btn', 'btn-add');
	btnAddDiv.innerText = "Добавить спецификацию";

	btnAddDiv.addEventListener('click', async () => {
		let spec = await createSpec(btnAddDiv);
		btnAddDiv.parentNode.insertBefore(spec, btnAddDiv);
	});

	categoryDiv.append(categoryFieldDiv, btnAddDiv);

	return categoryDiv;
}
