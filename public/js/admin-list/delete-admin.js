
let isDeleteSection = false;
let adminCards = document.querySelectorAll('.admin');
let deleteSection = document.querySelector(".delete-section")



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
	let deleteButton = adminCard.querySelector('.trash')
	deleteButton.addEventListener('click', (e)=> {
		const id = deleteButton.id;
		deleteAdminById(id,adminCard);
	})
	adminCard.addEventListener('drag', (e) =>{
		adminCard.style.borderColor = 'red'
	})
	adminCard.addEventListener('dragend', (e) =>{
		if (isDeleteSection === true)
		{
			const id = adminCard.id;
			deleteAdminById(id,adminCard);

		}
		adminCard.style.borderColor = 'white'
		isDeleteSection = false;
	})
}

function deleteAdminById(id,adminCard)
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
					adminCard.remove();
				} else {
					showPopup('Не удалось снять администратора с должности')
				}
			});
		})
}

