function showPopup(text) {
	let popup = document.createElement('div');
	popup.textContent = text;
	popup.classList.add('popup');
	document.querySelector('body').append(popup);
	setTimeout(() => {
		popup.classList.add('hidden');
	}, 2000);
	setTimeout(() => {
		popup.remove();
	}, 2100);
}
