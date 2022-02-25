let changeCards = document.querySelectorAll('.types-card');

for (let changeCard of changeCards)
{
	changeCard.addEventListener('click', (e)=>{
		url = new URL(window.location + 'catalog');
		url.searchParams.set('type', changeCard.id);
		console.log(changeCard.id)
		window.location=url
	})
}