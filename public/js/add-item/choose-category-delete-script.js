let categorySelector = document.querySelector('.input-category');
let btnSave = document.querySelector('a.btn');

categorySelector.addEventListener('change', (e) =>{
	btnSave.href = '/admin/deleteSpec/' + e.target.value;
});

window.onload = function() {
	btnSave.href = '/admin/deleteSpec/' + categorySelector.value;
};
