<?php $pageId = "game"; ?>
<?php $title = "PIX - Play!"; ?>
<?php include("incl/config.php"); ?>

<?php// $pageStyle = file_get_contents(__DIR__ . '/plugins/game/style.less'); ?>
<?php include("incl/header.php"); ?>

<!-- Main -->
	
</article>
		<div style="margin: 1em;">
			<canvas id="pix" width="500px" height="500px" style="margin:auto;">HTML5 Canvas is not supported by your browser.</canvas>
		</div>
</div>
<!-- end -->

<script src="<?=$gyg->path2url(__DIR__ . '/js/jquery.js')?>"></script>
<script src="<?=$gyg->path2url(__DIR__ . '/js/common.js')?>"></script>
<script src="<?=$gyg->path2url(__DIR__ . '/main.js')?>"></script>
<script src="<?=$gyg->path2url(__DIR__ . '/plugins/game/pix.js')?>"></script>

</body>

</html>