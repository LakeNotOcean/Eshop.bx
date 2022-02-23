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
}

function setLightMode() {
	appearance.href = '/css/appearance/light-theme.css';
	appearance.className = 'appearance-light';
	btnTheme.innerHTML = `
		<use xlink:href="/img/sprites.svg#moon"></use>
	`;
}
