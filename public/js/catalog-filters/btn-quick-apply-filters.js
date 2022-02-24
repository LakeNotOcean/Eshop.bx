let checkboxes = document.querySelectorAll('.category_checkbox');
let btnQuickApply = document.querySelector('.btn-quick-apply');

const btnQuickApplyOffsetY = getOffsetY(btnQuickApply) + getHeight(btnQuickApply)/4;
btnQuickApply.style.display = 'none';

document.querySelector('.filters').addEventListener('scroll', () => {
	btnQuickApply.style.display = 'none';
});
document.addEventListener('scroll', () => {
	btnQuickApply.style.display = 'none';
});

for (let checkbox of checkboxes)
{
	checkbox.addEventListener('click',() => showButton(checkbox));
}

function showButton(checkbox)
{
	if (checkbox.checked) {
		let y = getOffsetY(checkbox) - btnQuickApplyOffsetY;
		btnQuickApply.style.top = y + 'px';
		btnQuickApply.style.display = 'block';
	} else {
		btnQuickApply.style.display = 'none';
	}
}

function getOffsetY(element) {
	const bodyRect = document.body.getBoundingClientRect();
	const elemRect = element.getBoundingClientRect();
	return elemRect.top - bodyRect.top;
}

function getHeight(element) {
	return element.getBoundingClientRect().height;
}
