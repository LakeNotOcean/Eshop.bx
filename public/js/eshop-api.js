async function loadCategoryAndSpecByType(typeId){
	let r = await fetch('/categoriesByType?item-type=' + typeId);
	return  r.json();
}

async function loadAllCategory(){
	let r = await fetch('/category/detail');
	return r.json();
}