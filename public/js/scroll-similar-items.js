let scrollablePlace = document.querySelector('.similar-item-cards-section')
let similarItems = document.querySelectorAll(".similar-item-card")
let similarItemWidth = similarItems[0].offsetWidth
let leftSimilarItemScroll = document.querySelector(".similar-item-cards-left-arrow");
let rightSimilarItemScroll = document.querySelector(".similar-item-cards-right-arrow");

rightSimilarItemScroll.addEventListener('click', (e) => {
	console.log(scrollablePlace.scrollLeft)
	console.log(similarItemWidth * (similarItems.length + 2))
	if (scrollablePlace.scrollLeft <= similarItemWidth * (similarItems.length - 2))
	{
		scrollablePlace.scroll(scrollablePlace.scrollLeft + similarItemWidth + 20,0)
	}

})


leftSimilarItemScroll.addEventListener('click', (e) => {

		scrollablePlace.scroll(scrollablePlace.scrollLeft - similarItemWidth - 20,0)


})








