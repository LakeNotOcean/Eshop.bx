/**
 * @param {number} itemsAmount
 * @param {string[]} titles
 */
export function wordEndingResolver(itemsAmount, titles)
{
	{
		let cases = [2, 0, 1, 1, 1, 2];
		return titles[(itemsAmount % 100 > 4 && itemsAmount % 100 < 20) ? 2 : cases[Math.min(itemsAmount % 10, 5)]];
	}
}