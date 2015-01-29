<!doctype html>
<html lang='<?=$lang?>'>
	<head>
		<meta charset='utf-8'/>
		<style type = "text/css"><?=$style?></style>
		<title><?=$title?></title>
		<link rel = "shortcut icon" href = "<?=$favicon?>"/>
		<link rel = "stylesheet" type = "text/css" href="<?=$stylesheet?>"/>
	</head>

<body>
	<div id = "leftWrapper">
	</div>

	<div id = "centerWrapper">
		<div id = "header">		<?=$header?>	</div>
		<div id = "menu">		<?=$menu?>		</div>
		<div id = "content">	<?=$content?>	</div>
		<div id = "contentNav">	<?=$contentNav?></div>
		<div id = "footer">		<?=$footer?>	</div>
	</div>
	
	<div id = "rightWrapper">
		<div id = "above"><?=$above?></div>
	</div>
</body>

</html>