let formReview = document.querySelector('.review-send');
if(formReview !== null){
	formReview.querySelector('.btn-add').addEventListener('click', (e) =>{
		formReview.submit();
	});
}