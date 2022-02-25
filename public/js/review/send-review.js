const formReview = document.querySelector('.review-send');
if(formReview) {
	const btnAddReview = formReview.querySelector('.btn-add');
	if (btnAddReview) {
		btnAddReview.addEventListener('click', () =>{
			formReview.submit();
		});
	}
}
