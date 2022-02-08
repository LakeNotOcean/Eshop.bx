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
		btnAddSpec.parentNode.insertBefore(createSpec(), btnAddSpec);
	});
}

function createSpec() {
	let specDiv = document.createElement('div');
	specDiv.classList.add('spec');

	let fieldDiv = document.createElement('div');
	fieldDiv.classList.add('field');
	let specNameInput = document.createElement('input');
	specNameInput.type = "text";
	specNameInput.setAttribute('list', "spec-data")
	specNameInput.placeholder = "Выбрать название спецификации";
	let arrowSpan = document.createElement('span');
	arrowSpan.classList.add('arrow');
	fieldDiv.append(specNameInput, arrowSpan);

	let specValueInput = document.createElement('input');
	specValueInput.placeholder = "Ввести значение спецификации";

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

	let inputCategoryInput = document.createElement('input');
	inputCategoryInput.classList.add('input-category');
	inputCategoryInput.setAttribute('list', "category-data")
	inputCategoryInput.placeholder = "Выбрать название категории";

	let arrowSpan = document.createElement('span');
	arrowSpan.classList.add('arrow');

	fieldDiv.append(inputCategoryInput, arrowSpan);

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
		btnAddDiv.parentNode.insertBefore(createSpec(), btnAddDiv);
	});

	categoryDiv.append(categoryFieldDiv, btnAddDiv);

	return categoryDiv;
}