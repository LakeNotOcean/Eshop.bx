let inputMain = document.getElementById('main-image');
let previewMain = document.getElementById('main-image-preview');

inputMain.addEventListener('change', () => {
	while(previewMain.firstChild) {
		previewMain.removeChild(previewMain.firstChild);
	}
	const files = inputMain.files;
	if (files.length === 0) {
		const defaultImage = createImage("/img/default_image.webp", 'default');
		previewMain.appendChild(defaultImage);
	}
	for(const file of files) {
		const mainImage = createImage(URL.createObjectURL(file), file.name);
		previewMain.appendChild(mainImage);
	}

	const imageRemoveButtons = previewMain.querySelectorAll('.image-remove-btn');
	for (let imageRemoveBtn of imageRemoveButtons) {
		imageRemoveBtn.addEventListener('click', () => {
			inputMain.value = "";
			const imageContainer = previewMain.querySelector('.image-container');
			imageContainer.remove();
			const defaultImage = createImage("/img/default_image.webp", 'default');
			previewMain.appendChild(defaultImage);
		});
	}
});

let inputOther = document.getElementById('other-images');
let previewOther = document.getElementById('other-images-preview');
let oldImages = previewOther.querySelectorAll('.old-img');
oldImages.forEach((oldImage) => {
	oldImage.nextElementSibling.addEventListener('click', async () =>{
		deleteImageById(oldImage.name);
		oldImage.parentNode.remove();
	});
});

inputOther.addEventListener('change', () => {
	let images = previewOther.querySelectorAll('img');
	images.forEach((image) => {
		if(!image.classList.contains('old-img'))
			image.parentNode.remove();
	});
	const files = inputOther.files;
	if (files.length === 0 && !images.hasChildNodes()) {
		const message = document.createElement('div');
		message.classList.add('no-images-title');
		message.innerText = 'Добавьте дополнительные фотографии для товара';
		previewOther.appendChild(message);
	}
	for(const file of files) {
		const otherImage = createImage(URL.createObjectURL(file), file.name);
		previewOther.appendChild(otherImage);
	}
	let newImages = previewOther.querySelectorAll('.new-img');
	//const imageRemoveButtons = previewOther.querySelectorAll('.image-remove-btn');
	for (let newImage of newImages) {
		let imageRemoveBtn = newImage.nextElementSibling;
		imageRemoveBtn.addEventListener('click', () => {
			const imageContainer = imageRemoveBtn.parentNode;
			const image = imageContainer.querySelector('.image-img');
			removeFileFromInput(inputOther, image.alt);
			imageContainer.remove();
			if (previewOther.childNodes.length === 0) {
				const message = document.createElement('div');
				message.classList.add('no-images-title');
				message.innerText = 'Добавьте дополнительные фотографии для товара';
				previewOther.appendChild(message);
			}
		});
	}
});

function resetPreview()
{
	while(previewMain.firstChild) {
		previewMain.removeChild(previewMain.firstChild);
	}
	let defaultImage = createImage("/img/default_image.webp", 'default');
	previewMain.appendChild(defaultImage);

	while(previewOther.firstChild) {
		previewOther.removeChild(previewOther.firstChild);
	}
	const message = document.createElement('div');
	message.classList.add('no-images-title');
	message.innerText = 'Добавьте дополнительные фотографии для товара';
	previewOther.appendChild(message);
}

function createImage(path, alt)
{
	const div = document.createElement('div');
	div.className = "image-container";

	const image = document.createElement('img');
	image.src = path;
	image.alt = alt;
	image.classList.add('image-img');
	image.classList.add('new-img');
	div.append(image);

	const imageRemoveBtn = document.createElement('div');
	imageRemoveBtn.className = "image-remove-btn";
	div.append(imageRemoveBtn);

	return div;
}

function removeFileFromInput(input, fileName)
{
	const fileListArr = Array.from(input.files);
	for (let i = 0; i < fileListArr.length; i++) {
		if (fileListArr[i].name === fileName) {
			fileListArr.splice(i, 1);
			break;
		}
	}
	let dt = new DataTransfer();
	for (let file of fileListArr) {
		dt.items.add(file);
	}
	input.files = dt.files;
}
