let scrollablePlace = document.querySelector(".slider-wrapper")
let similarItems = document.querySelectorAll(".similar-item-card")
let scrollPage = 0
let leftSimilarItemScroll = document.querySelector(".similar-item-cards-left-arrow");
let rightSimilarItemScroll = document.querySelector(".similar-item-cards-right-arrow");

rightSimilarItemScroll.addEventListener('click', (e) => {
	const similarItemWidth = similarItems[0].offsetWidth;
	if (scrollPage < (similarItems.length-2))
	{
		scrollPage += 1;
		scrollablePlace.scroll((similarItemWidth + 20) * scrollPage, 0);
	}
})


leftSimilarItemScroll.addEventListener('click', (e) => {
	console.log(scrollPage)
	const similarItemWidth = similarItems[0].offsetWidth;
	if (scrollPage > 0)
	{
		scrollPage -= 1
		scrollablePlace.scroll((similarItemWidth + 20) * scrollPage,0)
	}

})

window.addEventListener("resize", (e) => {
	scrollablePlace.scroll(0,0)
})







