<?php

$database = mysqli_init();
$database->real_connect('localhost','test','test','test');
$result = $database->query("SELECT TITLE, PRICE, SHORT_DESC, FULL_DESC, SPECS FROM item;");
$items = [];
while ($itemData = mysqli_fetch_assoc($result))
{
	$itemData['SPECS'] = specsToArray($itemData['SPECS']);
	$items[] = $itemData;
}

function specsToArray(string $specsStr): array
{
	$specs = [];
	$categories = explode('<c>', $specsStr);
	foreach ($categories as $category)
	{
		$categoryData = explode("<n>", $category);
		$categoryName = $categoryData[0];
		$specArray = explode('<s>', $categoryData[1]);
		$categoryArray = [];
		foreach ($specArray as $spec)
		{
			$specData = explode('<v>', $spec);
			$specName = $specData[0];
			$specValue = $specData[1];
			$categoryArray[$specName] = $specValue;
		}
		$specs[$categoryName] = $categoryArray;
	}
	return $specs;
}

?>

<!doctype html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>test</title>
</head>
<body>

<a href="admin-side.php">Go to admin-side</a>

<?php foreach ($items as $item):?>
	<div class="item">
		<p>Название:<?= $item['TITLE']?></p>
		<p>Цена:<?= $item['PRICE']?></p>
		<p>Краткое описание:<?= $item['SHORT_DESC']?></p>
		<p>Описание:<?= $item['FULL_DESC']?></p>
		<h3>Характеристики</h3>
		<?php foreach ($item['SPECS'] as $categoryName => $category):?>
			<h4><?= $categoryName?></h4>
			<?php foreach ($category as $specName => $spec):?>
				<?= $specName . "..........." . $spec?><br>
			<?php endforeach;?>
		<?php endforeach;?>
	</div>
<?php endforeach;?>

<style>
	.item {
		background: #AFAFAF;
		padding: 10px;
		border-radius: 15px;
		margin: 10px;
	}
</style>

</body>
</html>
