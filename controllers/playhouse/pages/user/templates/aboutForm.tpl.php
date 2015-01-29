<!---Template for user profile on user page. --->

<h1>Edit about</h1>
<form id="about-form">	
	<textarea id="about" name="about"><?=$user['about']?></textarea>
	
	<input type="hidden" name="id" value="<?=$user['id']?>"/>
	
	<p><button type="button" id="save">Save</button></p>
	<p id="form-output"></p>
</form>