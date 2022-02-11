let categories;
let spec = {};

async function getSpecification(catId) {
	if(catId in spec) return spec[catId]
	let response = await fetch('/category/detail');
	spec = await response.json();
	return spec[catId];
}

function changeSpecValueInput(spec) {
	let input = spec.parentElement.parentElement.querySelector('input');
	let categoryId = spec.parentElement.parentElement.parentElement.querySelector('.input-category').value;
	let specId = spec.value;
	input.name = 'specs[' + categoryId + '][' + specId + ']';
}

fetch('/categories')
	.then((response) => response.json().then(json => {
		categories = json;
	}));

function getSpecOption(select, catId) {
	while (select.firstChild) select.lastChild.remove();
	getSpecification(catId).then(specs => {
		for(let specId in specs){
			let option = document.createElement('option');
			option.value = specId;
			option.text = specs[specId];
			select.append(option);
		}
		changeSpecValueInput(select);
	});

}

function changeChild(parent, catId){
	nodeList = parent.parentElement.parentElement.parentElement.querySelectorAll('.input-spec-name');
	nodeList.forEach(node => {
		getSpecOption(node, catId);
	});
}


let deleteSpec = document.querySelectorAll('.spec .delete');
for (let btnDeleteSpec of deleteSpec) {
	btnDeleteSpec.addEventListener('click', () => {
		btnDeleteSpec.parentNode.parentNode.removeChild(btnDeleteSpec.parentNode);
	});
}

let deleteCategory = document.querySelectorAll('.category-field .delete');
for (let btnDeleteCategory of deleteCategory) {
	btnDeleteCategory.addEventListener('click', () => {
		btnDeleteCategory.parentNode.parentNode.parentNode.removeChild(btnDeleteCategory.parentNode.parentNode);
	});
}

let addSpec = document.querySelectorAll('.category .add');
for (let btnAddSpec of addSpec) {
	btnAddSpec.addEventListener('click', () => {
		btnAddSpec.parentNode.insertBefore(createSpec(btnAddSpec), btnAddSpec);
	});
}

function createSpec(btn) {
	let specDiv = document.createElement('div');
	specDiv.classList.add('spec');

	let fieldDiv = document.createElement('div');
	fieldDiv.classList.add('field');
	let specNameInput = document.createElement('select');
	specNameInput.addEventListener('change', event => changeSpecValueInput(event.target));

	let category = btn.parentNode;
	let inputCategory = category.querySelector('.input-category');
	let categoryId = inputCategory.value;

	let specValueInput = document.createElement('input');
	specValueInput.placeholder = "Ввести значение спецификации";

	getSpecification(categoryId).then(specs => {
		for(let specId in specs){
			let option = document.createElement('option');
			option.value = specId;
			option.text = specs[specId];
			specNameInput.append(option);
		}
		changeSpecValueInput(specNameInput);
	});

	specNameInput.classList.add('input-spec-name');
	fieldDiv.append(specNameInput);

	let btnDeleteDiv = document.createElement('div');
	btnDeleteDiv.classList.add('btn', 'delete');
	btnDeleteDiv.innerText = "Удалить";

	btnDeleteDiv.addEventListener('click', () => {
		btnDeleteDiv.parentNode.parentNode.removeChild(btnDeleteDiv.parentNode);
	});

	specDiv.append(fieldDiv, specValueInput, btnDeleteDiv);
	return specDiv;
}

let addCategory = document.querySelectorAll('.add-category');
for (let btnAddCategory of addCategory) {
	btnAddCategory.addEventListener('click', () => {
		btnAddCategory.parentNode.insertBefore(createCategory(), btnAddCategory);
	});
}

function createCategory() {
	let categoryDiv = document.createElement('div');
	categoryDiv.classList.add('category');

	let categoryFieldDiv = document.createElement('div');
	categoryFieldDiv.classList.add('category-field');

	let fieldDiv = document.createElement('div');
	fieldDiv.classList.add('field');

	let inputCategoryInput = document.createElement('select');
	inputCategoryInput.classList.add('input-category');
	for(let categoryId in categories){
		let option = document.createElement('option');
		option.value = categoryId;
		option.text = categories[categoryId];
		inputCategoryInput.append(option);
	}

	inputCategoryInput.addEventListener('change', (event)=>{
		changeChild(event.target, event.target.value);
	});

	fieldDiv.append(inputCategoryInput);

	let btnDeleteDiv = document.createElement('div');
	btnDeleteDiv.classList.add('btn', 'delete');
	btnDeleteDiv.innerText = "Удалить";

	btnDeleteDiv.addEventListener('click', () => {
		btnDeleteDiv.parentNode.parentNode.parentNode.removeChild(btnDeleteDiv.parentNode.parentNode);
	});

	categoryFieldDiv.append(fieldDiv, btnDeleteDiv);

	let btnAddDiv = document.createElement('div');
	btnAddDiv.classList.add('btn', 'add');
	btnAddDiv.innerText = "Добавить спецификацию";

	btnAddDiv.addEventListener('click', () => {
		btnAddDiv.parentNode.insertBefore(createSpec(btnAddDiv), btnAddDiv);
	});

	categoryDiv.append(categoryFieldDiv, btnAddDiv);

	return categoryDiv;
}
