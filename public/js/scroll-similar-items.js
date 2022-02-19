let scrollablePlace = document.querySelector('.similar-item-cards-section')
let similarItem = document.querySelector(".similar-item-card")
let similarItemWidth = similarItem.offsetWidth
let leftSimilarItemScroll = document.querySelector(".similar-item-cards-left-arrow");
let rightSimilarItemScroll = document.querySelector(".similar-item-cards-right-arrow");

rightSimilarItemScroll.addEventListener('click', (e) => {
	console.log(scrollablePlace.scrollLeft)
	scrollablePlace.scroll(scrollablePlace.scrollLeft + similarItemWidth + 22,0)
})


leftSimilarItemScroll.addEventListener('click', (e) => {
	scrollablePlace.scroll(scrollablePlace.scrollLeft - similarItemWidth - 22,0)
})








