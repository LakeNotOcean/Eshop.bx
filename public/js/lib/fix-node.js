function fixNode(node, prevNode) {
	let prevNodeStyle = window.getComputedStyle(prevNode);

	let navNode = document.querySelector('nav');
	let navStyle = window.getComputedStyle(navNode);
	let offset = parseInt(navStyle.height) + parseInt(prevNodeStyle.marginTop) + parseInt(prevNodeStyle.marginBottom);

	let y = prevNode.getBoundingClientRect().bottom - parseInt(navStyle.height) + window.scrollY;

	document.addEventListener('scroll', () => {
		if (window.scrollY >= y) {
			node.style.position = "fixed";
			node.style.top = offset + "px";
		} else {
			node.style.position = "";
		}
	});
}
