let statusSelect = document.getElementById('statusSelect');

let params = new URLSearchParams(window.location.search);
if (params.has('status'))
{
	statusSelect.value = params.get('status');
}

statusSelect.addEventListener('change', () => {
	let query = window.location.search;
	let searchParams = new URLSearchParams(query)
	searchParams.set('status', statusSelect.value)
	window.location = location.pathname + '?' + searchParams.toString();
});
