const tagsContainer = document.querySelector('.tags-container');
const inputTag = tagsContainer.querySelector('input');

window.addEventListener('keydown', (e) => {
	if (e.key === "Backspace" && inputTag.value === "") {
		removeLastTag()
	}
	if (e.key === "Enter"){
		e.preventDefault();
		if(addTag(inputTag.value))
			inputTag.value = '';
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

	tagsContainer.insertBefore(tagNode, inputTag);
	return true;
}

function removeLastTag() {
	const createdTags = tagsContainer.querySelectorAll('.input-tag');
	createdTags[createdTags.length - 1].remove();
}
