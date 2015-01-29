<form id="comment-form">
	<h2>Post comment</h2>
	<input type="hidden" name="postId" value="<?=$postId?>"/>
	<input type="hidden" name="filter" value="markdown"/>
	
	<?php if($userSession->isLoggedIn()): ?>
		<input type="hidden" name="username" value="<?=$_SESSION['user']['name']?>">
		<input type="hidden" name="userId" value="<?=$userSession->get()['id']?>">
	<?php else: ?>
		<p><input type="text" name="username" value="Anonymous"></p>
		<input type="hidden" name="userId" value="">
	<?php endif; ?>
	<div><textarea id="comment-form-data" name="data"></textarea></div>

	<p><button type="button" id="comment-form-submit">Submit</button></p>
	<p id="form-output"></p>
</form>