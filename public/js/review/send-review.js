const formReview = document.querySelector('.review-send');
if(formReview) {
	const btnAddReview = formReview.querySelector('.btn-send-review');
	if (btnAddReview) {
		btnAddReview.addEventListener('click', () =>{
			sendSimpleForm(formReview, '/addReview', 'post').then(r => {
				r.json().then(json => printError(formReview.querySelector('.errors-container'), json));
			});
		});
	}
}
