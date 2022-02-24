let addAdmins = document.querySelectorAll('.add-admin');

for (let addAdmin of addAdmins)
{
	addAdmin.addEventListener('click', ()=>{
		alertDialogConfirm(
			'Добавление администратора',
			'Вы уверены, что хотите сделать этого пользователя администратором?', () => {
				let id = addAdmin.id;
				let postBody = new FormData();
				postBody.append('addAdmin', id);
				let token = document.querySelector('.token');
				postBody.append(token.name, token.value);
				fetch('/admin/userList', {
					method: 'post',
					body: postBody
				}).then((r) => {
					if (r.ok) {
						showPopup('Пользователь поставлен на должность администратора');
						location.reload();
					} else {
						showPopup('Не удалось поставить на должность администратора')
					}
				});
			});
	})
}
