let categoryInputs = document.querySelectorAll('.input-category');
for (let inputCategory of categoryInputs) {
	inputCategory.addEventListener('change', () => {
		let category = inputCategory.parentNode.parentNode.parentNode;
		let specNameInputs = category.querySelectorAll('.input-spec-name');
		console.log(inputCategory.value);
		for (let inputSpecName of specNameInputs) {
			inputSpecName.setAttribute('list', inputCategory.value+"-spec-data");
		}
	});
}
