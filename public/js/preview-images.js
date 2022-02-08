let inputMain = document.getElementById('main-image');
let previewMain = document.getElementById('main-image-preview');

inputMain.addEventListener('change', () => {
	while(previewMain.firstChild) {
		previewMain.removeChild(previewMain.firstChild);
	}
	const files = inputMain.files;
	if (files.length === 0) {
		const image = document.createElement('img');
		// image.src = "./img/default_image.webp";
		image.src = "../../public/img/default_image.webp";
		image.alt = "main-image";
		image.classList.add('image-img');
		previewMain.appendChild(image);
	}
	for(const file of files) {
		const image = document.createElement('img');
		image.src = URL.createObjectURL(file);
		image.alt = "main-image";
		image.classList.add('image-img');
		previewMain.appendChild(image);
	}
});

let inputOther = document.getElementById('other-images');
let previewOther = document.getElementById('other-images-preview');

inputOther.addEventListener('change', () => {
	while(previewOther.firstChild) {
		previewOther.removeChild(previewOther.firstChild);
	}
	const files = inputOther.files;
	if (files.length === 0) {
		const message = document.createElement('div');
		message.classList.add('no-images-title');
		message.innerText = 'Добавьте дополнительные фотографии для товара';
		previewOther.appendChild(message);
	}
	for(const file of files) {
		const image = document.createElement('img');
		image.src = URL.createObjectURL(file);
		image.alt = "other-image";
		image.classList.add('image-img');
		previewOther.appendChild(image);
	}
});
