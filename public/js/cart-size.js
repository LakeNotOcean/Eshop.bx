let cartIcon = document.querySelector('.cart');

new MutationObserver((mutations) => {
	for (const m of mutations) {
		if (m.type !== "attributes" || m.attributeName !== "data-cart-size") {
			return
		}
		if (cartIcon.getAttribute('data-cart-size') > 0) {
			cartIcon.classList.remove('empty-cart');
		} else {
			cartIcon.classList.add('empty-cart');
		}
	}
}).observe(cartIcon, { attributes: true });
