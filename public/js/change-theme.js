let btnTheme = document.querySelector('.btn-theme');
let appearance = document.getElementById('appearance');

btnTheme.addEventListener('click', () => {
	if (appearance.className === 'appearance-dark') {
		setLightMode()
	} else {
		setDarkMode()
	}
})

function setDarkMode() {
	appearance.href = '/css/appearance/dark-theme.css';
	appearance.className = 'appearance-dark';
	btnTheme.innerHTML = `
		<use xlink:href="/img/sprites.svg#sun"></use>
	`;
	setCookie('darkMode', 1, 365);
}

function setLightMode() {
	appearance.href = '/css/appearance/light-theme.css';
	appearance.className = 'appearance-light';
	btnTheme.innerHTML = `
		<use xlink:href="/img/sprites.svg#moon"></use>
	`;
	setCookie('darkMode', 0, 365);
}

function setCookie(name, value, days) {
	const date = new Date();
	date.setTime(date.getTime() + (days*24*60*60*1000));
	document.cookie = name + "=" + value +  "; expires=" + date.toUTCString() + "; path=/";
}
