const tagsContainer = document.querySelector('.tags-container');
const inputTags = tagsContainer.querySelector('input');

inputTags.addEventListener('keydown', (e) => {
	if (e.key === "Backspace" && inputTags.value === "") {
		removeLastTag();
	}
	if (e.key === "Enter"){
		e.preventDefault();
		if(addTag(inputTags.value)) {
			inputTags.value = '';
		}
		return false;
	}
})

function addTag(tagValue) {
	tagValue = tagValue.trim()
	if (tagValue === '') {
		return false;
	}
	const existingTags = tagsContainer.childNodes;
	for (let existingTag of existingTags) {
		if (existingTag.innerText === tagValue) {
			return false;
		}
	}

	const tagNode = document.createElement('span');
	tagNode.className = 'input-tag';
	tagNode.innerText = tagValue;
	tagNode.addEventListener('click', () => {
		tagNode.remove();
	})

	tagsContainer.insertBefore(tagNode, inputTags);
	return true;
}

function removeLastTag() {
	const createdTags = tagsContainer.querySelectorAll('.input-tag');
	if (createdTags) {
		createdTags[createdTags.length - 1].remove();
	}
}
