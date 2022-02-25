function clearQuery(url){
	url.searchParams.forEach(function(value,key){
		url.searchParams.delete(key)
	})
}