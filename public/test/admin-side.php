<?php

/*==========================
CREATE DATABASE
==========================*/
$database = mysqli_init();
$database->real_connect('localhost','test','test','test');

/*==========================
GET ITEM FIELDS
==========================*/
$item_title = $_REQUEST['item_title'] ?? "";
$item_price = $_REQUEST['item_price'] ?? "";
$item_shortDesc = $_REQUEST['item_short_desc'] ?? "";
$item_fullDesc = $_REQUEST['item_full_desc'] ?? "";
$item_specs = specsToString($_REQUEST['item_specs'] ?? []);

/*==========================
ADD ITEM
==========================*/
if ($item_title && $item_price && $item_shortDesc && $item_fullDesc && $item_specs)
{
	$database->query("
		INSERT INTO item (TITLE, PRICE, SHORT_DESC, FULL_DESC, SPECS)
		VALUES ('$item_title', '$item_price', '$item_shortDesc', '$item_fullDesc', '$item_specs');"
	);
}

/*==========================
GET TEMPLATE
==========================*/
$resultTemplate = $database->query("SELECT NAME, TEMPLATE FROM spec_template;");
$templates = [];
while ($template = mysqli_fetch_assoc($resultTemplate))
{
	$templates[$template['NAME']] = $template['TEMPLATE'];
}
$specTemplate = $_REQUEST['spec_template'] ?? array_key_first($templates);
$currentTemplate = templateToArray($templates[$specTemplate]);

/*==========================
FUNCTIONS
==========================*/
function templateToArray(string $templateStr): array
{
	$template = [];
	$categories = explode('<c>', $templateStr);
	foreach ($categories as $category)
	{
		$categoryData = explode("<n>", $category);
		$name = $categoryData[0];
		$specs = explode('<s>', $categoryData[1]);
		$template[$name] = $specs;
	}
	return $template;
}

function specsToString(array $specs): string
{
	$str_specs = "";
	foreach ($specs as $categoryName => $category)
	{
		$str_category = "";
		foreach ($category as $spec => $value)
		{
			$str_spec = $spec . '<v>' . $value;
			$str_category .= '<s>' . $str_spec;
		}
		$str_category = substr($str_category, 3);
		$str_specs .= '<c>' . $categoryName . '<n>' . $str_category;
	}
	return substr($str_specs, 3);
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

<a href="user-side.php">Go to user-side</a>

<h3>Выберите шаблон для добавления товара</h3>
<?php foreach ($templates as $id => $template):?>
	<input type="radio" id="<?= $id?>" name="template" <?= $id === $specTemplate ? "checked" : ""?>><?= $id?><br>
<?php endforeach;?>

<form action="admin-side.php" method="post" enctype="multipart/form-data">
	<input type="text" name="item_title" placeholder="Название товара"><br>
	<input type="number" name="item_price" placeholder="Стоимость товара"><br>
	<input type="text" name="item_short_desc" placeholder="Короткое описание"><br>
	<input type="text" name="item_full_desc" placeholder="Описание"><br>

	<h3>Характеристики</h3>
	<?php foreach ($currentTemplate as $name => $specs):?>
		<div><?= $name?></div>
		<?php foreach ($specs as $spec):?>
			<input type="text" name="item_specs[<?= $name?>][<?= $spec?>]" placeholder="<?= $spec?>"><br>
		<?php endforeach;?>
	<?php endforeach;?>

	<input type="submit" value="Добавить товар">
</form>

<script>
	let radioBtn = document.querySelectorAll('input[name="template"]');
	for (let btn of radioBtn) {
		btn.addEventListener('click', function() {
			window.location.replace('admin-side.php?spec_template=' + btn.id);
		});
	}
</script>

</body>
</html>
