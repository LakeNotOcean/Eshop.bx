let scrollablePlace = document.querySelector(".slider-wrapper");
let similarItems = document.querySelectorAll(".similar-item-card");
let scrollPage = 0;
let leftSimilarItemScroll = document.querySelector(".similar-item-cards-left-arrow");
let rightSimilarItemScroll = document.querySelector(".similar-item-cards-right-arrow");

if(rightSimilarItemScroll !== null){
	rightSimilarItemScroll.addEventListener('click', () => {
		const similarItemWidth = similarItems[0].offsetWidth;
		if (scrollPage < (similarItems.length-2))
		{
			scrollPage += 1;
			scrollablePlace.scroll((similarItemWidth + 20) * scrollPage, 0);
		}
	});
}

if(leftSimilarItemScroll !== null){
	leftSimilarItemScroll.addEventListener('click', () => {
		const similarItemWidth = similarItems[0].offsetWidth;
		if (scrollPage > 0)
		{
			scrollPage -= 1;
			scrollablePlace.scroll((similarItemWidth + 20) * scrollPage,0);
		}
	});
}


window.addEventListener("resize", () => {
	scrollPage = 0;
	scrollablePlace.scroll(0,0)
})
