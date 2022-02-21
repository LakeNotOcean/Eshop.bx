let scrollablePlace = document.querySelector(".slider-wrapper")
let similarItems = document.querySelectorAll(".similar-item-card")

let leftSimilarItemScroll = document.querySelector(".similar-item-cards-left-arrow");
let rightSimilarItemScroll = document.querySelector(".similar-item-cards-right-arrow");

rightSimilarItemScroll.addEventListener('click', (e) => {
	const similarItemWidth = similarItems[0].offsetWidth;
	console.log(scrollablePlace.scrollLeft)
	console.log(similarItemWidth * (similarItems.length + 2))
	if (scrollablePlace.scrollLeft <= similarItemWidth * (similarItems.length - 2))
	{
		scrollablePlace.scroll(scrollablePlace.scrollLeft + similarItemWidth + 20,0)
	}

})


leftSimilarItemScroll.addEventListener('click', (e) => {
	const similarItemWidth = similarItems[0].offsetWidth;
		scrollablePlace.scroll(scrollablePlace.scrollLeft - similarItemWidth - 20,0)
})

window.addEventListener("resize", (e) => {
	scrollablePlace.scroll(0,0)
})







