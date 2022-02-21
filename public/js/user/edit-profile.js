
let userInfos = document.querySelectorAll('.user-info');

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

		let token = document.querySelector('.token');
		postBody.append(token.name, token.value);
		let inputs = userInfo.querySelectorAll('input');
		for (let input of inputs) {
			postBody.append(input.id, input.value);
		}

		fetch('/updateUser', {
			method: 'post',
			body: postBody
		}).then((r) => {
			if (r.ok) {
				popup('Профиль изменён');
				closeUserInfoEditor(userInfo)
				updateInterface(postBody);
			} else {
				popup('Профиль изменить не удалось')
			}
		});
	})
}

function updateInterface(postBody)
{
	for (let pair of postBody.entries()) {
		let userInfoValue = document.querySelector('.' + pair[0]);
		if (userInfoValue)
		{
			userInfoValue.innerText = pair[1] + " ";
		}
	}
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

