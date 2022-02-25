let inputTag = document.getElementById('item-tags');

let tags = [];

window.addEventListener('keydown', (e) => {
	if (e.key === "Backspace" && inputTag.value === "") {
		//removeTag
	}
	if (e.key === "Enter"){
		e.preventDefault();
		if(addTag(inputTag.value))
			inputTag.value = '';
		return false;
	}
})

function addTag() {

}
