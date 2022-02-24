async function deleteReview(id, csrf){
	let data = new FormData();
	data.append('csrf_token', csrf);
	return fetch('/reviewDelete/' + id, {method: 'POST', body: data});
}

document.querySelectorAll('.review-remove-btn').forEach((btn) => {
	btn.addEventListener('click', (e) => {
		let review_id = btn.parentElement.querySelector('[name=review_id]').value
		let csrf = document.querySelector('.token');
		deleteReview(review_id, csrf).then((r) => {
			if(r.ok){
				showPopup('Отзыв успешно удален');
				setTimeout(() => location.reload());
			}
			else
				showPopup('Отзыв не удалось удалить');
		});
	});
});
