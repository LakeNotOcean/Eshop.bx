let allCategory = {};
let templateCategory = {};

async function loadCategoryAndSpecByType(typeId){
	let r = await fetch('/categoriesByType?item-type=' + typeId);
	return  r.json();
}

async function loadAllCategory(){
	let r = await fetch('/category/detail');
	return r.json();
}

async function getCategory()
{
	if (Object.keys(allCategory).length != 0)
	{
		return allCategory;
	}
	allCategory = await loadAllCategory();
	return allCategory;
}

async function getSpecification(catId)
{
	if (catId in allCategory)
	{
		return allCategory[catId];
	}
	allCategory = await loadAllCategory();
	return allCategory[catId];
}