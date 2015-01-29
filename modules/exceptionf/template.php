<!doctype html>
<html lang='en'>
<head>
	<meta charset='utf-8'/>
	<title>EXCEPTION</title>
</head>

<body>
	<h1>EXCEPTION</h1>
	<p><?=@$class?><?=@$type?><?=$function?> (<?=$line?>): <?=$file?></p>
	<div id="source"></div>
	<div>
		<p><?=$message?></p>
	</div>
	<br>
	<h2>Stack trace</h2>
	<table>
	<?php foreach($trace as $i => $data): ?>
		<tr>
			<td><p>#<?=$i?>: </p></td>
			<td><p><?=@$data['class']?><?=@$data['type']?><?=$data['function']?> (<?=$data['line']?>)</p></td>
			<td><p><?=$data['file']?></p></td>
		</tr>
	<?php endforeach;?>
	</table>
</body>

</html>