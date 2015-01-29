$(document).ready(function()
{
'use strict';


(function($) 
{	
	function success(data)
	{
		if(data.success)
			$('#above-login').trigger('click');
		else
			$('#form-output').html(data.output);
	}
	
	function register()
	{
		var data = $('#user-create-form').serialize();
	
		$.ajax(
		{
			type: 'post',
			url: '/ajax/playhouse/user?insert',
			data: data,
			dataType: 'json',
			success: success
		}); 
	}
	
	$('#user-create-form').keypress(function(e)
	{
		// Unbind enter from post submission. 13 is the keycode for enter.
		if(e.keyCode === 13)
			return false;
	});
	
	
	$('#user-form-register').click(register);
}) (jQuery);
});

