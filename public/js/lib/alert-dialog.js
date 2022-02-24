
function alertDialog(message) {

}

function alertDialogDelete(title, message, action) {
	if (document.querySelector('.alert-dialog')) {
		return;
	}
	const body = document.querySelector('body');
	const dialog = document.createElement('div');
	dialog.classList.add('alert-dialog', 'card', 'card-outline');
	dialog.innerHTML = `
		<div class="dialog-title">${title}</div>
		<div class="dialog-message">${message}</div>
		<div class="dialog-buttons">
			<div class="btn btn-delete">Удалить</div>
			<div class="btn btn-cancel">Отмена</div>
		</div>
	`;
	const btnDelete = dialog.querySelector('.btn-delete');
	btnDelete.addEventListener('click', action);

	const buttons = dialog.querySelectorAll('.btn');
	buttons.forEach(btn => {
		btn.addEventListener('click', () => {
			dialog.remove();
		});
	});

	body.append(dialog)
}
