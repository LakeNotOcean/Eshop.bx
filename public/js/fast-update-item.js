async function fastUpdateItem(data){
	return fetch('/admin/fastUpdateItem', {method: 'POST', body: data});
}

document.querySelectorAll('[name=fast-update]').forEach((form) => {
	form.addEventListener('submit', (e) =>{
		e.preventDefault();
		let data = new FormData();
		form.querySelectorAll('input, textarea, select').forEach((input) => {
			data.append(input.name, input.value);
		});
		fastUpdateItem(data).then((r) => {
			if(r.ok){
				popup('Товар сохранен');
				setTimeout(() => location.reload(), 500)
			}else {
				popup('Не удалось сохранить товар');
			}
		});
	});
});