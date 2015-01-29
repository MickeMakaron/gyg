<!doctype html>
<html lang='<?=$lang?>'>
	<head>
		<meta charset='utf-8'/>
		<meta name="viewport" content="width=device-width; initial-scale=0.5, maximum-scale=0.5, minimum-scale=0.5; " />
		<title><?php if(isset($title)) echo "{$title} | ";?><?=$baseTitle?></title>
		<meta name="description" content="<?=$description?>" />
		<meta name="keywords" content="<?=implode(', ', $keywords)?>" />
		<base href="<?=$baseUrl?>">
			
		<link rel = "shortcut icon" href = "<?=$favicon?>"/>
		<link rel = "stylesheet" type = "text/css" href="<?=$stylesheet?>"/>
		
		
		<style type = "text/css"><?=$style?></style>
		
		<?php foreach($scripts as $script): ?>
		<script type = "text/javascript" src="<?=$script?>"></script>
		<?php endforeach; ?>
	</head>

<body>


	<div id = "wrapper">
		<div id = "above"><?=$above?></div>
		<div id = "headerWrapper">
		

				<div id = "speechBubble">
					<div id = "headerSplash" class = "shadow playhouseLink"><?=$splash?></div>
					<div id = "headerSpeechBubblePoint"></div>
				</div>


				<table id="header">
					<tr>
						<td id = "headerImage" class = "shadow">
							<img src="<?=$headerImage?>">
						</td>		
						<td id = "headerTitle">
							<a href = "<?=$home?>"><?=$header?></a>
						</td>
					</tr>
				</table>
			
			<div id = "menu">
				<?=$menu?>
			</div>
		</div>	


		
		<div id = "contentWrapper" class = "shadow">
			<div id = "content">	<?=$content?>	</div>
		<!--	<div id = "contentNav">	<?//=$contentNav?></div> -->
		</div>

		
		<div id = "footer" class = "shadow">	
			<div id = "copyright"><?=$copyright?></div>
			<div id = "poweredBy"><?=$poweredBy?></div>
		</div>
		
	</div>
</body>

</html>