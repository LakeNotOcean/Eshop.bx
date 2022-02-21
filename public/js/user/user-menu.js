let profileNavItem = document.getElementById('userMenu');

let uri = window.location.pathname;

let menuItems = profileNavItem.querySelectorAll('.menu-item');
for (let menuItem of menuItems) {
	if (menuItem.getAttribute('href') === uri) {
		let label = profileNavItem.querySelector('.nav-item-label');
		label.innerText = menuItem.textContent;
		profileNavItem.classList.add('nav-item-active');
	}
}
