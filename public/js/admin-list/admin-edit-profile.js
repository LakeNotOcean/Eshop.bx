
let userInfos = document.querySelectorAll('.user-info');
let userId = document.querySelector('.user-id')
for (let userInfo of userInfos)
{
	let btnChange = userInfo.querySelector('.btn-change');
	btnChange.addEventListener('click', () => {
		if (btnChange.innerText === 'Редактировать') {
			openUserInfoEditor(userInfo);
		} else {
			closeUserInfoEditor(userInfo);
		}
	});

	let btnSave = userInfo.querySelector('.btn-save');
	btnSave.addEventListener('click', () => {
		let postBody = new FormData();
		let id = userId.id;
		let token = document.querySelector('.token');
		postBody.append(token.name, token.value);
		let inputs = []
		if (btnSave.classList.contains('btn-save-role'))
		{
			inputs = userInfo.querySelectorAll('select');
		}
		else
		{
			inputs = userInfo.querySelectorAll('input');
		}
		for (let input of inputs) {
			postBody.append(input.id, input.value);
		}

		fetch('/admin/adminUpdateUser/'+id, {
			method: 'post',
			body: postBody
		}).then((r) => {
			if (r.ok) {
				showPopup('Профиль изменён');
				closeUserInfoEditor(userInfo)
				location.reload();
			} else {
				showPopup('Профиль изменить не удалось')
			}
		});
	})
}



function openUserInfoEditor(userInfo)
{
	userInfo.querySelector('.btn-change').innerText = 'Отменить';
	userInfo.querySelector('.user-info-value').classList.add('gone');
	userInfo.querySelector('.user-info-change').classList.remove('gone');
}

function closeUserInfoEditor(userInfo)
{
	userInfo.querySelector('.btn-change').innerText = 'Редактировать';
	userInfo.querySelector('.user-info-value').classList.remove('gone');
	userInfo.querySelector('.user-info-change').classList.add('gone');
}

