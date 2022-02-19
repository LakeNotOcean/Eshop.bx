let similarItems = document.querySelectorAll(".similar-item-card")
let leftSimilarItemScroll = document.querySelector(".similar-item-cards-left-arrow");
let rightSimilarItemScroll = document.querySelector(".similar-item-cards-right-arrow");

rightSimilarItemScroll.addEventListener('click', (e) => {
	let elementCount = Number(0);
	for (let currentSimilarItem of similarItems)
	{
		let activeItems = document.querySelectorAll(".similar-item-active");
		if (currentSimilarItem.classList.contains("similar-item-active") && elementCount < 2 && (activeItems.length !== 1) && (similarItems.length > 2))
		{
			elementCount +=1;
			currentSimilarItem.classList.remove("similar-item-active")
			let activeId = currentSimilarItem.id;
			if (similarItems.length > (Number(activeId) + Number(2)))
			{

				similarItems[(Number(activeId) + Number(2))].classList.add("similar-item-active")
			}
		}
	}



})

leftSimilarItemScroll.addEventListener('click', (e) => {
	for (let currentSimilarItem of similarItems)
	{
		let activeItems = document.querySelectorAll(".similar-item-active");
		let alreadyActivated = -1;
		if (currentSimilarItem.classList.contains("similar-item-active") && (similarItems.length > 2) && (Number(currentSimilarItem.id) !== alreadyActivated) && (Number(currentSimilarItem.id)!== Number(0)) && (Number(currentSimilarItem.id)!== Number(1)))
		{
			if (activeItems.length === 1)
			{
				currentSimilarItem.classList.remove("similar-item-active")
				let activeId = currentSimilarItem.id;
				similarItems[(Number(activeId) - Number(2))].classList.add("similar-item-active");
				similarItems[(Number(activeId) - Number(1))].classList.add("similar-item-active");
			}
			else
			{
				currentSimilarItem.classList.remove("similar-item-active")
				let activeId = currentSimilarItem.id;
				similarItems[(Number(activeId) - Number(2))].classList.add("similar-item-active");
				alreadyActivated = (Number(activeId) - Number(2))
			}
		}
	}
})








