let navItems = document.querySelectorAll('.nav-item');

let uri = window.location.pathname;

let foundNavItem = false;
for (let navItem of navItems) {
	let menuItems = navItem.querySelectorAll('.menu-item');
	for (let menuItem of menuItems) {

		let matchId = false;
		if (menuItem.id !== '') {
			matchId = checkUri(menuItem.id, uri)
		}

		if (menuItem.getAttribute('href') === uri || matchId) {
			let label = navItem.querySelector('.nav-item-label');
			label.innerText = menuItem.innerHTML;
			navItem.classList.add('nav-item-active');
			foundNavItem = true;
		}
		if (foundNavItem) {
			break;
		}
	}
	if (foundNavItem) {
		break;
	}
}

function checkUri(contains, str)
{
	let uriRegex = new RegExp(contains);
	let matches = uriRegex.exec(str);
	return matches != null;
}
