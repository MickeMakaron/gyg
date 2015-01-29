<div id="comments">
	<h2>Comments</h2>
	<?php foreach($comments as $c): extract($c);?>
		<div class="comment post">
			<div><?=$data?></div>
			<div id="post-subtitle">by 
			<?php if($userKey): ?>
				<a href="<?=$userKey?>"><?=$username?></a>
			<?php else: ?>
				<?=$username?>
			<?php endif; ?>
			, <?=$created?></div>
		</div>
	<?php endforeach; ?>
</div>
<?php include("createComment.tpl.php"); ?>