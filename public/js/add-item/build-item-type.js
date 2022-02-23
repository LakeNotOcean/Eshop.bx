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
	let nodeList = parent.parentElement.parentElement.parentElement.querySelectorAll('.input-spec-name');
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
	specDiv.innerHTML = `
		<div class="field">
			<select name="template-specs[]" class="input-spec-name">
				<option value="10">Подсветка</option>
			</select>
		</div>
		<div class="btn btn-delete">Удалить</div>
	`;

	let selectSpecName = specDiv.querySelector('.input-spec-name');

	let category = btn.parentNode;
	let inputCategory = category.querySelector('.input-category');
	let categoryId = inputCategory.value;

	await getSpecOption(selectSpecName, categoryId);

	let btnDeleteDiv = specDiv.querySelector('.btn-delete');
	btnDeleteDiv.addEventListener('click', () => {
		specDiv.remove();
	});

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
	categoryDiv.innerHTML = `
		<div class="category-field">
			<div class="field">
				<select class="input-category"></select>
			</div>
			<div class="btn btn-delete">Удалить</div>
		</div>
		<div class="btn btn-add">Добавить спецификацию</div>
	`;

	let categories = await getCategory();
	let selectCategory = categoryDiv.querySelector('.input-category');

	for (let categoryId in categories) {
		const option = document.createElement('option');
		option.value = categoryId;
		option.innerText = categories[categoryId][0];
		selectCategory.append(option);
	}

	selectCategory.addEventListener('change', (event)=>{
		changeChild(event.target, event.target.value);
	});

	let btnDeleteCategory = categoryDiv.querySelector('.btn-delete');
	btnDeleteCategory.addEventListener('click', () => {
		categoryDiv.remove();
	});

	let btnAddSpec = categoryDiv.querySelector('.btn-add');
	btnAddSpec.addEventListener('click', async () => {
		let spec = await createSpec(btnAddSpec);
		btnAddSpec.parentNode.insertBefore(spec, btnAddSpec);
	});
	return categoryDiv;
}
