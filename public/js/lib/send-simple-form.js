function sendSimpleForm(form, path, method){
	let data = new FormData();
	form.querySelectorAll('input, select, textarea').forEach((el) =>{
		if(el.type === 'radio' && !el.checked) return
		data.append(el.name, el.value);
	});
	return fetch(path, {body: data, method: method});
}

function printError(parentNode, groupErrors){
	while (parentNode.firstChild) parentNode.lastChild.remove();
	let errorCategories = [];
	let list = document.createElement('ul');
	list.classList.add('list-errors');
	for(let errors in groupErrors){
		errorCategories.push(errors);
		for(let error in groupErrors[errors]){
			let listEl = document.createElement('li');
			listEl.classList.add('list-error');
			listEl.textContent = groupErrors[errors][error];
			list.append(listEl);
		}
	}
	parentNode.append(list);
	return errorCategories;
}