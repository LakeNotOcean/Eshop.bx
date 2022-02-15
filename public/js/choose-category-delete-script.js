let categorySelector = document.querySelector('.input-category');
let btnSave = document.querySelector('.btn-save');

categorySelector.addEventListener('change', (e) =>{
	btnSave.href = '/admin/deleteSpec/' + e.target.value;
});

btnSave.href = '/admin/deleteSpec/' + categorySelector.options[0].value;