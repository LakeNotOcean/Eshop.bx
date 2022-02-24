
let isDeleteSection = false;
let deleteButtons = document.querySelectorAll(".trash");
let adminCards = document.querySelectorAll('.admin');
let deleteSection = document.querySelector(".delete-section")

for (let deleteButton of deleteButtons)
{
	deleteButton.addEventListener('click', (e)=> {
	const id = deleteButton.id;
	deleteAdminById(id);
	})
}

deleteSection.addEventListener('dragover', (e) => {
	e.preventDefault();
})

deleteSection.addEventListener("drop", (e,ui)=>
{
	e.preventDefault();
	isDeleteSection = true;
})

for (let adminCard of adminCards)
{
	adminCard.addEventListener('drag', (e) =>{
		adminCard.style.borderColor = 'red'
	})
	adminCard.addEventListener('dragend', (e) =>{
		if (isDeleteSection === true)
		{
			const id = adminCard.id;
			deleteAdminById(id);
		}
		isDeleteSection = false;
	})
}

function deleteAdminById(id)
{
	alertDialogDelete(
		'Удаление администратора',
		'Вы уверены, что хотите удалить этого пользователя из списка администраторов?',
		() => {
			let postBody = new FormData();
			postBody.append('deleteAdmin', id);
			let token = document.querySelector('.token');
			postBody.append(token.name, token.value);
			fetch('/admin/adminList', {
				method: 'post',
				body: postBody
			}).then((r) => {
				if (r.ok) {
					showPopup('Администратор снят с должности');
					location.reload();
				} else {
					showPopup('Не удалось снять администратора с должности')
				}
			});
		})
}
