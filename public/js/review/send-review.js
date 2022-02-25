const formReview = document.querySelector('.review-send');
if(formReview) {
	const btnAddReview = formReview.querySelector('.btn-send-review');
	if (btnAddReview) {
		btnAddReview.addEventListener('click', () =>{
			formReview.submit();
		});
	}
}
