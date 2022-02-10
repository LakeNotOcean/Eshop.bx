let scrollMenuItems = document.querySelectorAll('.scroll-menu-item');

for (let scrollMenuItem of scrollMenuItems) {
	let id = scrollMenuItem.title;
	let anchor = document.getElementById(id);
	let y = anchor.getBoundingClientRect().top;
	scrollMenuItem.addEventListener('click', () => {
		window.scroll({top: y,behavior: 'smooth'});
	});
}
