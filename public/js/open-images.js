let images = document.querySelectorAll('.main-image, .other-image');
let openedImages = document.querySelector('.opened-images');
let btnBack = document.querySelector('.btn-back');
let container = document.querySelector('.container');

for (let image of images) {
	image.addEventListener('click', () => {
		openedImages.style.display = 'flex';
		openedImages.classList.remove('come-out');
		openedImages.classList.add('come-in');

		container.classList.add('collapse');
	});
}

btnBack.addEventListener('click', () => {
	openedImages.classList.add('come-out');
	openedImages.classList.remove('come-in');

	container.classList.remove('collapse');
});
