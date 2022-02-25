let changeCards = document.querySelectorAll('.types-card');

for (let changeCard of changeCards)
{
	changeCard.addEventListener('click', (e)=>{
		let url = new URL('catalog',window.location.origin);
		url.searchParams.set('type', changeCard.id);
		window.location=url;
	})
}