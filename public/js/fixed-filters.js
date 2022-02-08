let filters = document.querySelector('.filters');

let countNode = document.querySelector('.search_result_count');
let countStyle = window.getComputedStyle(countNode);
let y = parseInt(countStyle.height) + parseInt(countStyle.marginTop);

let navNode = document.querySelector('nav');
let navStyle = window.getComputedStyle(navNode);
let offset = parseInt(navStyle.height) + parseInt(countStyle.marginTop);

document.addEventListener('scroll', () => {
	if (window.scrollY >= y) {
		filters.style.position = "fixed";
		filters.style.top = offset + "px";
	} else {
		filters.style.position = "";
	}
});
